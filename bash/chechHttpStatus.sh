#!/usr/bin/env bash

# Follow the fail-fast paradigm when applicable.
# Append "|| true" if you expect an error.
# See also common pitfalls for errexit: http://mywiki.wooledge.org/BashFAQ/105
set -o errexit

# Initialize all variables and guard against typos.
set -o nounset

# The script fails if any of the commands in pipe fail and the return value of
# a pipeline is the value of the last (rightmost) command to exit with
# a non-zero status, or zero if all commands in the pipeline exit successfully.
set -o pipefail

# Exit on error inside any functions or subshells and inherit the ERR trap.
set -o errtrace

# Turn on traces, useful while debugging but commented out by default.
# set -o xtrace

time=$(date +"%Y%m%d%H00")
query="textgen?language=en&product=iltaan_asti&forecasttime=${time}"

declare -a locations=(
  "Kaakkois-Suomi"
  "Etelä-Savo"
  "Kanta-Häme"
  "Kainuu"
  "Kymenlaakso"
  "Päijät-Häme"
  "Rovaniemen%20seutu"
  "Itä-Lappi"
  "Kemi-Tornio"
  "Torniolaakso"
  "Tunturi-Lappi"
  "Pohjois-Lappi"
  "Pohjois-Pohjanmaan%20länsiosa"
  "Pohjois-Pohjanmaan%20itäosa"
  "Pohjanmaa"
  "Pohjois-Karjala"
  "Keski-Pohjanmaa"
  "Keski-Suomi"
  "Meri-Lappi"
  "Länsi-Lappi"
  "Pohjois-Savo"
  "Satakunta"
  "Pirkanmaa"
  "Varsinais-Suomi"
  "Pääkaupunkiseutu"
  "Itä-Uusimaa"
)

for location in "${locations[@]}"; do
  res=$(curl -s -o /dev/null -w "%{http_code}\n" "http:/smartmet.fmi.fi/${query}&area=${location}")
  echo -e "location: $location\t\tHTTP status code: $res"
done