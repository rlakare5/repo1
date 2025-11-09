# Security Considerations

## Initial Setup Security

### Default Admin Account

This application creates an admin account during initial setup with the following **secure process**:

1. **Random Password Generation**: A unique 16-character random password is generated using cryptographically secure random bytes
2. **One-time Display**: The password is shown only once on the first-time setup page
3. **Automatic Deletion**: The password file is permanently deleted after viewing
4. **Forced Password Change**: On first login, the admin is REQUIRED to change the password before accessing any functionality
5. **One-time Creation**: The admin account is only created once when the `.setup_required` flag file exists
6. **Cannot Reuse Temporary**: The password change form tracks the temporary password and can enforce non-reuse

**This eliminates the "publicly known default password" vulnerability.**

### Security Best Practices

**For Local Development (XAMPP):**
- ✅ Change the default password immediately after setup
- ✅ Do not expose your local server to the public internet
- ✅ Use strong passwords (minimum 8 characters, recommended 12+)
- ✅ Keep your XAMPP installation updated

**For Production Deployment:**
- ⚠️ **DO NOT USE** this application in production without proper security hardening
- ⚠️ Change all default credentials before exposing to the internet
- ⚠️ Use HTTPS/SSL certificates
- ⚠️ Implement IP whitelisting for admin panel
- ⚠️ Add CAPTCHA to login form
- ⚠️ Implement rate limiting
- ⚠️ Use strong, unique passwords
- ⚠️ Regular security updates and monitoring

## Code Security Features

### SQL Injection Prevention
- All database queries use prepared statements with parameter binding
- No direct SQL string concatenation with user input

### Password Security
- Passwords are hashed using PHP's `password_hash()` function (bcrypt)
- Password verification uses `password_verify()`
- Minimum password length: 8 characters
- Session-based authentication

### Session Security
- Admin authentication uses PHP sessions
- Session variables track admin ID, username, and password change requirement
- Logout properly destroys session

### Error Handling
- Database connection errors are caught with try/catch
- Graceful redirect to setup page when database is unavailable
- User-friendly error messages (no stack traces exposed)

## Vulnerability Disclosure

This is an educational/portfolio project. Known limitations:

1. **Default Credentials**: Initial admin account uses known password until changed
2. **No Rate Limiting**: Login attempts are not rate-limited
3. **No CSRF Protection**: Forms do not implement CSRF tokens
4. **No Two-Factor Authentication**: Only password-based authentication
5. **Session Fixation**: No session regeneration after login
6. **No Account Lockout**: No protection against brute force attacks

## Recommended Enhancements for Production

If you plan to use this in a production environment, implement:

1. **Remove Default Admin Creation**: Require manual admin account creation
2. **CSRF Tokens**: Add CSRF protection to all forms
3. **Rate Limiting**: Implement login attempt limits
4. **Account Lockout**: Lock accounts after failed login attempts
5. **Session Regeneration**: Regenerate session ID after login
6. **HTTPS Only**: Force HTTPS for all connections
7. **Security Headers**: Add Content-Security-Policy, X-Frame-Options, etc.
8. **Input Validation**: Additional validation on all user inputs
9. **Audit Logging**: Log all admin actions
10. **Regular Updates**: Keep PHP and dependencies updated

## Reporting Security Issues

If you discover a security vulnerability, please:
1. Do not open a public issue
2. Contact the maintainer privately
3. Provide detailed information about the vulnerability

## License & Disclaimer

This software is provided "as is" for educational purposes. The authors are not responsible for any security breaches or data loss resulting from the use of this software.
