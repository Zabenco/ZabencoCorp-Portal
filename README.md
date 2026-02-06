# ZabencoCorp Portal

Corporate intranet portal with database connectivity.

## Features

- **Dashboard** - System status overview with real-time stats
- **Employee Directory** - Browse all active employees with contact info
- **IT Support Center** - Submit and track IT support tickets
- **HR Portal** - PTO requests, benefits, employee handbook
- **Document Center** - Company documents, policies, and templates
- **Career Hub** - Internal job postings and training programs
- **Company Policies** - Active company policies and guidelines

## Database Schema

### Tables
- `employees` - Employee directory
- `departments` - Department information
- `it_tickets` - IT support tickets
- `documents` - Document management
- `policies` - Company policies
- `job_postings` - Internal job board

### Views
- `v_employee_directory` - Active employee listing
- `v_open_tickets` - Open IT support tickets

## Setup Instructions

### 1. Clone the Repository
```bash
git clone https://github.com/Zabenco/ZabencoCorp-Portal.git
cd ZabencoCorp-Portal
```

### 2. Configure Database Connection

Edit `db-connect.php` and update the database credentials:

```php
$host = 'zabencocorp-db-01.cfioyem0wxq4.us-east-2.rds.amazonaws.com';
$dbname = 'zabencocorp_portal';
$user = 'zabencocorp';
$password = 'YOUR_DB_PASSWORD_HERE';
```

### 3. Deploy to EC2

```bash
# Connect to your EC2 instance
ssh -i zabencocorp.pem ec2-user@DEV_EC2_IP

# Clone the repository
git clone https://github.com/Zabenco/ZabencoCorp-Portal.git /var/www/html/
```

### 4. Install PHP PostgreSQL Extension

```bash
# Amazon Linux 2023
sudo dnf install php-pgsql -y
sudo systemctl restart httpd
```

### 5. Test the Portal

Access the portal at:
```
http://YOUR_EC2_PUBLIC_IP/
```

## AWS Infrastructure

| Resource | ID/URL |
|----------|--------|
| VPC | vpc-076c28a73b54d4700 |
| EC2 Dev | i-0572be1f671a63243 |
| RDS PostgreSQL | zabencocorp-db-01 |
| S3 Bucket | zabencocorp-portal |

## Security Notes

- Database credentials should be stored securely
- Use environment variables in production
- Enable SSL/TLS for production deployments
- Restrict database access via security groups

## License

Internal corporate use only.
