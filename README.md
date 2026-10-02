# Grid Element Trash (WordPress-Plugin)

Grid Element Trash extends the [Grid](https://wordpress.org/plugins/grid/) plugin with a
trash for container and box types. It is available on
[WordPress.org](https://wordpress.org/plugins/grid-element-trash/).

## Why

A Grid installation can accumulate container and box types that an editor should no
longer be able to add. This plugin adds a *Trash* screen under the Grid menu where an
administrator hides individual containers, reusable containers and boxes from the Grid
toolbar without deleting any stored content.

## How it works

The settings screen (`Grid → Trash`, capability `manage_options`) lists the container
types, reusable containers and box types Grid reports. Un-checking an entry stores a
site option (`grid_element_trash_<element>_<type>`) via an authenticated AJAX call;
Grid's listing filters (`grid_boxes_search`, `grid_metaboxes`, `grid_containers`,
`grid_reusable_containers`, …) then drop the trashed entries. Deleting the plugin
removes every `grid_element_trash_*` option.

The plugin stores no content and changes nothing but these options.

## Requirements

The [Grid](https://wordpress.org/plugins/grid/) plugin must be installed and active.

## Repository layout

`public/` is exactly what ships to wordpress.org; everything else is repository-only.
`plugin.php` in the root is a development wrapper that loads `public/`, so the whole
repository can be symlinked into `wp-content/plugins` during development.

The main file `public/grid-element-trash.php` must keep its name — WordPress identifies
an installed plugin by `<directory>/<main file>` and renaming it deactivates the plugin
on every site at the next update.

Releases are cut by release-please from conventional commits and deployed to the
wordpress.org SVN by GitHub Actions — see
[.github/WORKFLOWS.md](.github/WORKFLOWS.md). Contribution rules are in
[CONTRIBUTING.md](CONTRIBUTING.md).

## License

GPL-3.0-or-later, see [LICENSE](LICENSE).
