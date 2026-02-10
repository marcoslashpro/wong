use chrono::{DateTime, Datelike, Timelike, Utc};
use std::{
    env::{args, home_dir},
    fs::{OpenOptions, create_dir},
    io::{self, BufWriter},
    path::PathBuf,
};
use std::io::Write;

fn main() {
    let Some(path_to_home) = home_dir() else {
        println!("Unable to locate the path to the home directory");
        return;
    };
    println!("Found path to home: {path_to_home:#?}");

    let cli_input: Vec<String> = args().collect();
    println!("Gathered CLI inputs: {cli_input:#?}");

    let notes_folder_name = String::from(".wong");
    let notes_folder_dir = match setup_notes_folder(path_to_home, notes_folder_name) {
        Ok(dir) => dir,
        Err(e) => {
            println!("Unable to notes folder, full error: {e:?}");
            return;
        }
    };
    println!("Created notes folder dir: {notes_folder_dir:?}");
}

fn setup_notes_folder(root_path: PathBuf, folder_name: String) -> io::Result<PathBuf> {
    let path_to_notes_folder = root_path.join(folder_name);
    if path_to_notes_folder.exists() {
        return Ok(path_to_notes_folder);
    }
    return match create_dir(&path_to_notes_folder) {
        Ok(_) => Ok(path_to_notes_folder),
        Err(e) => Err(e),
    };
}

fn write_note(note_dir: PathBuf, content: String) -> io::Result<()> {
    let today = Utc::now();
    let note_title = format!(
        "{}-{}-{}",
        today.year().to_string(),
        today.month().to_string(),
        today.day().to_string()
    );
    let note_path = note_dir.join(note_title);
    let note_file = OpenOptions::new()
        .append(true)
        .create(true)
        .open(note_path)?;
    let note_header = format!(
        "[{}-{}]",
        today.hour().to_string(),
        today.minute().to_string()
    );
    let final_content = format!("{}\n{}\n\n", note_header, content);
    let mut file_writer = BufWriter::new(note_file);
    writeln!(file_writer, "{}", final_content);
}
