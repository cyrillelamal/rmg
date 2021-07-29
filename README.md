# rmg -r

> ReMove Glob --Recursively.

This scripts acts like rm -rf, but it looks for files in subdirectories.

For example, this is very useful to remove .DS_Store from your Windows PS after you have visited your PC using the OS X
remote.

```
php rmg -g .DS_Store -s ./Music
```

or using shebang

```
rmg -g .DS_Store -s ./Music
```

## Parameters

### Required

`-g --glob` - [the glob pattern to match](https://www.php.net/manual/en/function.glob.php).

`-s --start` - the directory where to start from.

### Optional

`-v --verbose` - if set, print the progress to the stdout.
