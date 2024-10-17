#

## Helpful docs for dangerous PHP function

https://gist.github.com/mccabe615/b0907514d34b2de088c4996933ea1720

## Other tools

https://github.com/Cvar1984/sussyfinder -> php written

https://github.com/FloeDesignTechnologies/phpcs-security-audit -> use php token

## Dangerous function

### Remote Code Execution (RCE)

| Function | Parameters | Description | Official doc |
| -------- | ---------- | ----------- | ----------------- |
| shell_exec | string $command | Execute command via shell and return the complete output as a string | [Link](https://www.php.net/manual/en/function.shell-exec.php) |
| exec | Execute the given command | string $command, array &$output = null, int &$result_code = null | [Link](https://www.php.net/manual/en/function.exec.php) |
| system | Execute an external program and display the output | string $command, int &$result_code = null | [Link](https://www.php.net/manual/en/function.system.php) |
| passthru | Execute an external program and display raw output | string $command, int &$result_code = null | [Link](https://www.php.net/manual/en/function.passthru.php) |
| pcntl_exec |  Executes the program with the given arguments | string $path, array $args = [], array $env_vars = [] | [Link](https://www.php.net/manual/en/function.pcntl-exec.php) |
| eval | Evaluate a string as PHP code | string $code | [Link](https://www.php.net/manual/en/function.eval.php) |


[Backtick operaor](https://www.php.net/manual/en/language.operators.execution.php) execute command like shell_exec.