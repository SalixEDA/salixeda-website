# salixeda-website
Official SalixEDA website source. Hosting documentation, downloads, and community resources.

## Website Engine and the Site Itself
There was a project called GetSimple. It's a file-based content management system.
Currently, the project has stalled, but I decided to implement the ideas embedded in it
on my own website.

### Main Engine Concept
PHP is chosen as the execution system. The key principles are:
- zero dependencies on external resources or libraries
- maximum static nature
- multilingualism "out of the box"
- ease of maintenance

### Organization Principle
The entry point for all page requests is index.php. It parses the target page URL and
attempts to find a suitable template in the pages directory. The template defines the
structure of a specific page. For example, it makes sense for all articles to look
structurally similar. This is exactly what the page template provides. Files containing
text content are stored separately.

## Installation
For installation instructions see [installation.md](installation.md)

