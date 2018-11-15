#!/bin/bash

FAIL=0

echo Parallel bash test
(time v1=$(sleep 1)) &
(time v2=$(sleep 2)) &

for job in $(jobs -p)
do
    echo $job
    wait $job || let "FAIL+=1"
done

echo Failed: $FAIL

if [[ $FAIL -ne 0 ]]; then
    echo Something went wrong
fi
