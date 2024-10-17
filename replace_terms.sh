#!/bin/bash

# Check if exactly 2 arguments are provided
if [ "$#" -ne 2 ]; then
  echo "Usage: $0 oldname newname"
  exit 1
fi

# Assign the arguments to variables
oldname="$1"
newname="$2"

# Function to check if a file is binary
is_text_file() {
  file --mime-type "$1" | grep -q 'text/'
}

# Step 1: Change all occurrences of oldname in file contents (case-sensitive)
echo "Replacing all occurrences of $oldname with $newname in file contents..."
find . -type f | while read -r file; do
  if is_text_file "$file"; then
    grep -q "$oldname" "$file" && sed -i '' "s/${oldname}/${newname}/g" "$file"
  fi
done

# Step 2: Rename files and directories containing oldname (case-sensitive)
echo "Renaming files and directories containing $oldname..."
find . -depth -name "*${oldname}*" | while read -r file; do
  newpath=$(echo "$file" | sed "s/${oldname}/${newname}/g")
  mv "$file" "$newpath"
done

echo "Replacement complete."

