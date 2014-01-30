swtcontact
==========

Wordpress Plug-In: Forward all contact requests to Swissistent Tasks

Version
=======

0.1 Initial

0.2 Minor installation bugfixes

0.3 Bugfix: Remove unused function
    Handle crlf properly
    Allow multiple filter criteria

0.3 Implement ignore patterns for email-subject (that confirmations are still sent to the user)


Installation
============
1. Download ZIP from github
2. Unzip file
3. Rename folder to swtcontact
4. Upload it into wordpress' wp-content/plugins directory
5. Active the plugin in wordpress' admin-console (Plugins / Installed Plugins)
6. Enter username and password of Swissistent Tasks (if you don't have credentials just contact Swissistent GmbH)
7. If required enter a ignore pattern: Emails with this subject are still sent per email.
8. Press: Save
9. Now the plugin fetches projects, groups and categories
10. Select the according values and enter passwort AGAIN
11. Press: Save

Now every contact request is forwarded to Swissistent Tasks

Security
=============
Passwort is hashed!
For security reasons we recommend ask Swissistent GmbH for a technical user with the minimum of the neccessary rights. 

Initial filter criteria that could be used
==========================================
Thank you for your Message!
[<blog-name>] New User Registration
[<blog-name>] Registrierung eines neuen Benutzers
[<blog-name>] Dein Benutzername und Passwort
