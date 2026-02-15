use chrono::{DateTime, Datelike, Timelike, Utc, Weekday};
use std::io::Write;
use std::path::Path;
use std::{
    env::{args, home_dir},
    fs::{OpenOptions, create_dir_all},
    io::{self, BufWriter},
    path::PathBuf,
};

fn main() {
    let Some(path_to_home) = home_dir() else {
        println!("Unable to locate the path to the home directory");
        return;
    };
    println!("Found path to home: {path_to_home:#?}");

    let cli_input: Vec<String> = args().collect();
    println!("Gathered CLI inputs: {cli_input:#?}");

    let notes_folder = path_to_home.join(Path::new(".wong"));
    let new_note = Note::new(&notes_folder, &Utc::now(), &cli_input[1][..]);
    match new_note.create() {
        Ok(_) => match new_note.write() {
            Ok(_) => {
                println!(
                    "New note created successfully at path: {}",
                    new_note.path_to().display()
                );
            }
            Err(e) => {
                println!("Unable to write to the note, full error: {e:#?}")
            }
        },
        Err(e) => {
            println!("Unable to create the note, full error: {e:#?}.");
        }
    }
}

struct Note<'a> {
    root_dir: &'a Path,
    year: i32,
    month: u32,
    weekday: Weekday,
    day: u32,
    time: CreationTime,
    content: &'a str,
}

struct CreationTime {
    hour: u32,
    minute: u32,
}

impl<'a> Note<'a> {
    fn new(root_dir: &'a Path, creation_date: &DateTime<Utc>, content: &'a str) -> Note<'a> {
        Note {
            root_dir: root_dir,
            year: creation_date.year(),
            month: creation_date.month(),
            day: creation_date.day(),
            weekday: creation_date.weekday(),
            time: CreationTime {
                hour: creation_date.hour(),
                minute: creation_date.minute(),
            },
            content,
        }
    }

    fn create(&self) -> io::Result<()> {
        create_dir_all(self._get_directory_structure())
    }

    fn write(&self) -> io::Result<()> {
        let note_file = OpenOptions::new()
            .append(true)
            .create(true)
            .open(self.path_to())?;
        let mut file_writer = BufWriter::new(note_file);
        writeln!(file_writer, "{}\n{}", self._get_note_title(), self.content)?;
        return Ok(());
    }

    fn path_to(&self) -> PathBuf {
        self._get_directory_structure()
            .join(self._get_note_file_name())
    }

    fn _get_directory_structure(&self) -> PathBuf {
        self.root_dir
            .join(self.year.to_string())
            .join(self.month.to_string())
    }

    fn _get_note_file_name(&self) -> String {
        format!("{} {}", self.weekday.to_string(), self.day.to_string())
    }

    fn _get_note_title(&self) -> String {
        format!(
            "[{}h-{}m]",
            self.time.hour.to_string(),
            self.time.minute.to_string()
        )
    }
}
