# Config Check(er)

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/0841f476d70a40e181e7c42685ba979f)](https://www.codacy.com/app/Idrinth/config-check?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Idrinth/json-check&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/0841f476d70a40e181e7c42685ba979f)](https://www.codacy.com/app/Idrinth/config-check?utm_source=github.com&utm_medium=referral&utm_content=Idrinth/json-check&utm_campaign=Badge_Coverage)
[![Build Status](https://travis-ci.org/Idrinth/config-check.svg?branch=master)](https://travis-ci.org/Idrinth/config-check)

This project is checking any project's config files for correctness, including schema validation once implemented.

## Supported formats and checks

| Format | File | Parseable | Schema |
| ------------- | ------------- | ------------- | ------------- |
| JSON | X | X | JSON Schema |
| YAML | X | X | (JSON Schema planned) |
| INI | X | X | (JSON Schema planned) |
| XML | X | X | DTD, (XSD planned) |

## Configuration

Use a .idrinth-cc.json to configure what and how to check, see the one in the root of this repository and the [schema](https://github.com/Idrinth/config-check/blob/master/src/schema.json) to see what is possible.


