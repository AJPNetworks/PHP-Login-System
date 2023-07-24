# Databases


## Accounts Table

Column Name | Data Type | Constraints | Description
------------|----------|-------------|------------
id | int | NOT NULL, AUTO_INCREMENT | The unique identifier for each account.
fullname | varchar(255) | NOT NULL | The full name of the user.
email | varchar(255) | NOT NULL | The user's email address used during registration.
username | varchar(255) | NOT NULL | The username chosen by the user during registration.
password | varchar(255) | NOT NULL | The password hash of the user's chosen password.
account_access_key | varchar(4096) | DEFAULT NULL | The browser cookie for user authentication.
mfs_token | varchar(255) | DEFAULT NULL | Multi-Factor Authentication token.
psw_reset_token | varchar(255) | DEFAULT NULL | Password reset token.
special | varchar(255) | DEFAULT NULL | Special account information.
locked | varchar(255) | DEFAULT NULL | Indicates if an account is allowed login or blocked.

## Example Entry for an Account

Here is an example entry in the accounts table:

| id  | fullname      | email              | username | password                               | account_access_key | mfs_token | psw_reset_token | special | locked  |
|----|---------------|-------------------|----------|----------------------------------------|-------------------|-----------|-----------------|---------|---------|
| 104 | FirstName\|LastName | email@example.com | Username | $2y$10$fRy53SsmwhGtWA.cgEp9Ge/auNyw2yEYdWdP/WaZTgi5NzeFlLz4C | NULL              | NULL      | NULL            | nvEmail | NULL    |

In this example, the password is hashed using bcrypt, and the fullname field contains "FirstName|LastName" as a special account note. Other fields like account_access_key, mfs_token, psw_reset_token, special, and locked have not been set in this entry.
