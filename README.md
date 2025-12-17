# Library Notifications Service

A small Yii2-based back office for managing authors and books, assigning multiple authors per title, and notifying subscribed readers (via SMS placeholder) whenever new books go live. It targets publishing teams who need a lightweight way to curate catalog data and keep subscribers informed.

## Running Locally
1. **Install PHP dependencies**
   ```bash
   cd app
   composer install
   ```
2. **Start the stack**
   ```bash
   docker-compose up -d --build
   ```
3. **Run database migrations**
   ```bash
   docker-compose exec php php yii migrate
   ```
4. **Log in**
   - URL: http://localhost:8080
   - Credentials: `admin` / `admin`
   - Default phone: `+79990000000`

Uploads (book covers) live in `storage/uploads/`, which is bind-mounted to `/var/www/html/web/uploads`. MySQL listens on `33060`, and the PHP container exposes port `8080`.

## Core Screens & Behavior
- **Home (`/site/index`)**: Welcome screen with quick guidance. Accessible to everyone.
- **Authors (`/author/index`)**: List and search all authors. Authenticated users can create, edit, delete, and from the detail page subscribe/unsubscribe. Subscriptions persist in `author_subscription`, and listeners later react to new books.
- **Books (`/book/index`)**: All titles with cover thumbnails. Logged-in users can add/edit/delete books, select multiple authors per book, and upload a cover (JPG/PNG/WebP ≤2 MB). When a book is created the `book.created` event triggers a listener that iterates author subscribers and logs a placeholder SMS payload (where an external provider would be called).
- **Top Authors Report (`/report/top-authors`)**: Public report showing the top 10 authors by number of releases in a selected year (pull-down filter). Counts aggregate through the `book_author` junction table.
- **Health (`/site/health`)**: JSON heartbeat for container monitoring.

Permissions:
- Guests can browse public pages (home, author/book listings, detail views, reports).
- Authenticated users gain CRUD access plus subscription management.

That’s the whole system—compose up, migrate, log in, add authors/books, and watch the event logs to see SMS notifications being “sent” to subscribers.***
