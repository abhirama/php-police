php-police
==========

This script checks for outgoing php svn changes and prints the names of the files which use in_array, array_key_exists and array_merge functions. The script scans all the changes from the directory where it is present and below it, hence the ideal place to run this script would be the root of your svn repo.