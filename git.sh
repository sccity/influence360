#!/bin/bash

if [ -z "$1" ] || [ -z "$2" ]; then
  echo "Usage: ./git <commit_description> <branch_name>"
  exit 1
fi

MSG=$1
BRANCH=$2

git pull --rebase origin "$BRANCH"
git add .
git commit -m "$MSG"
git push origin "$BRANCH"
