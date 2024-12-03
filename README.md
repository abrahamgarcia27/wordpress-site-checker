# WordPress Site Checker

This script allows you to upload a CSV file containing website URLs and determines whether each site is powered by WordPress. It generates a CSV file with the results, which you can download directly.

---

## Features

- **WordPress Detection**:
  - Identifies WordPress-powered websites by checking:
    - HTTP headers for `X-Powered-By: WordPress`.
    - `<meta name="generator" content="WordPress">` tag in the HTML.
    - Common WordPress paths (`/wp-content/`, `/wp-includes/`, `/wp-admin/`).
- **CSV Upload**: Accepts a file containing website URLs for batch processing.
- **Downloadable Results**: Outputs a CSV file with a summary of the findings.

---

## Requirements

- PHP 7.0 or higher
- A web server (e.g., Apache, Nginx)

---

## Installation

1. Clone or download the repository.
2. Place the script in your web server directory (e.g., `/var/www/html/` for Apache).
3. Ensure file upload permissions are enabled on your server.

---

## Usage

1. **Prepare Your CSV File**:

   - Create a CSV file with one column listing website URLs.
   - Example:
     ```
     website.com
     example.org
     blogsite.net
     ```

2. **Access the Script**:

   - Open the script in your browser (e.g., `http://localhost/wordpress-checker/index.php`).

3. **Upload the CSV**:

   - Use the file upload form on the page.
   - Submit the file for processing.

4. **Download Results**:
   - After processing, the script will generate a results file in CSV format for download.
   - The results file contains two columns: the website URL and whether it is powered by WordPress.

---

## Example Output

If you upload the following CSV:

```
example.com
wordpresssite.org
staticwebsite.net
```

The output CSV will look like this:

```
site,is_wordpress
example.com,No
wordpresssite.org,Yes
staticwebsite.net,No
```

---

## File Structure

- **index.php**: The main script file containing PHP logic and HTML for the upload form.

---

## Troubleshooting

1. **CSV File Not Processing**:
   - Ensure the file is formatted correctly with one website URL per row.
2. **No Results**:
   - Verify that the websites are accessible from your server.
   - Check if the server allows outbound HTTP requests (cURL must be enabled).
3. **Permission Issues**:
   - Ensure your web server has permissions to read/write uploaded files.

---

## License

This project is open-source and available for personal and commercial use. Feel free to modify and redistribute the script.

---

## Contributing

Have ideas for improvements? Feel free to fork the repository and submit a pull request. Feedback is always welcome!

---

## Contact

If you encounter issues or have suggestions, please reach out to the project maintainer.
