<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notes App</title>
  <link rel="stylesheet" href="styles.css">
  <script src="https://unpkg.com/htmx.org@1.9.10"></script>
</head>

<body>
  <div class="container">
    <!-- Sidebar with notes list -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2>Notes</h2>
        <form action="/notes/" method="post" class="new-note-form">
          <button type="submit" class="btn-new-note" title="Create new note">+</button>
        </form>
      </div>
      <div class="notes-list">
        <?php foreach ($notes as $listing_note): ?>
          <?php if ($listing_note['title']) { ?>
            <div class="note-item-wrapper">
              <form action="/" method="get" class="note-select-form">
                <input type="hidden" name="note_id" value="<?php echo $listing_note['id'] ?>">
                <button type="submit" class="note-item">
                  <?php echo $listing_note['title'] ?>
                </button>
              </form>
              <button
                class="btn-delete-note"
                title="Delete note"
                hx-delete="/notes/<?php echo $listing_note['id'] ?>"
                hx-target="closest .note-item-wrapper"
                hx-swap="outerHTML swap:1s"
                hx-confirm="Are you sure you want to delete this note?">×</button>
            </div>
          <?php } else {
          } ?>
        <?php endforeach; ?>
      </div>
    </aside>

    <!-- Main content area -->
    <main class="main-content">
      <form action="/notes/<?= $note['id'] ?>" method="post" class="note-form">
        <!-- Title input -->
        <div class="form-group">
          <input
            type="text"
            name="title"
            class="note-title"
            placeholder="Note Title"
            value="<?php echo $note['title']; ?>">
        </div>

        <!-- Content textarea -->
        <div class="form-group form-group-content">
          <textarea
            name="content"
            class="note-content"
            placeholder="Start typing your note..."><?php echo $note['content']; ?></textarea>
        </div>

        <!-- Submit button -->
        <div class="form-actions">
          <button type="submit" class="btn-save">Save Note</button>
        </div>
      </form>
    </main>
  </div>
</body>

</html>