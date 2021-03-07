# 台股資訊 API

此專案使用 PHP 8.0 + Laravel 8 撰寫，主要運行在 console 指令上。

## 安裝環境

使用 Composer 建置專案：

```
composer install
```

使用指令入口 `pastock` 即可知道有什麼指令可以使用：

```
$ php pastock
Laravel Framework 8.31.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help         Displays help for a command
  list         Lists commands
 stock
  stock:query  Stock Query
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
