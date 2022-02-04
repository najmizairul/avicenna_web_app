#!/bin/bash

docker run --rm -it -v $(pwd)/../.:/data/project/ \
  -p 8080:8080 jetbrains/qodana-php --show-report

docker run --rm -it -v $(pwd)/../.:/data/project/ \
  -p 8081:8080 jetbrains/qodana-license-audit --show-report
