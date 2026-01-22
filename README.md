# Moodle Data Extractor

A simple web tool to extract student data from Moodle Assignment HTML files and match it with an Excel student list.

## Features

- Upload base Excel file with student information
- Upload multiple Moodle HTML assignment files
- Extract usernames from each HTML file
- Match usernames with student codes and generate score column
- Export results as Excel file with automatic scoring (10 for match, 0 for no match)

## Requirements

- PHP >= 8.3
- PhpSpreadsheet library
- PHP Extensions: DOM, SimpleXML

## Installation

1. Install dependencies:
```bash
composer install
```

2. Place files in your web server directory

3. Access via browser: `http://localhost/ExtractIn4NTUMoodle/`

## Usage

1. Select base Excel file (.xls/.xlsx) containing student list
2. Select one or more HTML files from Moodle
3. Click "Process & Download Excel"
4. System automatically matches usernames and generates scoring

## File Structure

- `index.php` - Web interface
- `extract.php` - HTML parsing functions
- `process.php` - Upload and Excel processing
- `composer.json` - Dependencies

## License

MIT License - Nguyen Hai Trieu (2025)

## For

Information Technology Department - Nha Trang University
