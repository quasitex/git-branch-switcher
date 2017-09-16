# branch-switcher
Visual switcher for Git branches

## Introduction
Designated for developers who do eintensive testing and frequently switching branches on the server.
Shows the drop-down with the list of repository branches (can show local only or remotes as well).
See __httpdocs/example.html__

## Requirements
Client side script uses jQuery (version not matters) and jQuery Cookie plugin.
Server side made using PHP.

## Installation
1. Put __httpdocs/branch-switcher__ directory to your website root (it should be accessible as __/branch-switcher__)
2. Make __httpdocs/branch-switcher/cache__ directory writable (use __chmod 777__ command)
3. Copy __httpdocs/gitignore.sample__ to __httpdocs/.gitignore__ (or update exiting .gitignore file) to prevent branch-switcher code changes when branch changed
4. Copy git-hook/post-checkout.sample to Git hooks directory (eq. ./git/hook/) and rename it to post-checkout
5. Adjust __./git/hook/post-checkout__ file to match your website configuration (use Option 1 or Option 2 from file)
6. Include following code to <head> of your website:
```html
<link rel="stylesheet" type="text/css" href="branch-switcher/switcher.css"/>
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="branch-switcher/switcher.js"></script>
```
You may have already jQuery included, in that case you do not need to include
```html
<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
```

## Revisions history
### 0.9
	Initial release