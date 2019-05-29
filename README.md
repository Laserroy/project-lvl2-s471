[![Build Status](https://travis-ci.com/Laserroy/project-lvl2-s471.svg?branch=master)](https://travis-ci.com/Laserroy/project-lvl2-s471)
[![Maintainability](https://api.codeclimate.com/v1/badges/bfe4a5b672cb50ecfd72/maintainability)](https://codeclimate.com/github/Laserroy/project-lvl2-s471/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/bfe4a5b672cb50ecfd72/test_coverage)](https://codeclimate.com/github/Laserroy/project-lvl2-s471/test_coverage)

## Package Descrition

A simple cli utility for generating difference between config files.

Supported extensions:

+ json
+ yml
+ ini

### Installation

```xterm
composer global require lazeroy/gendiff
```

[![asciicast](https://asciinema.org/a/248976.svg)](https://asciinema.org/a/248976)

### Manual

```xterm
gendiff -h
```

[![asciicast](https://asciinema.org/a/248981.svg)](https://asciinema.org/a/248981)

#### Difference in nested format example

```xterm
gendiff '/path/first_config' '/path/second_config'
```

[![asciicast](https://asciinema.org/a/248986.svg)](https://asciinema.org/a/248986)

#### Difference in plain format example

```xterm
gendiff --format plain '/path/first_config' '/path/second_config'
```

[![asciicast](https://asciinema.org/a/248987.svg)](https://asciinema.org/a/248987)

#### Difference in json format example

```xterm
gendiff --format json '/path/first_config' '/path/second_config'
```

[![asciicast](https://asciinema.org/a/248990.svg)](https://asciinema.org/a/248990)