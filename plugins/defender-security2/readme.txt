=== Defender Security, Monitoring, and Hack Protection ===
Plugin Name: Defender Security, Monitoring, and Hack Protection
Version: 2.0.1
Author: WPMU DEV
Author URI: http://premium.wpmudev.org/
Contributors: WPMUDEV
Tags: Security, Security Tweaks, Hardening, IP lockout, Monitoring, Blacklist, Site Protection, Hacked, Security Scan
Requires at least: 4.6
Tested up to: 4.9.8
Stable tag: 2.0.1
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Protect WordPress from hackers with security tweaks, code scans, 2-Step Verification, IP lockouts, and monitoring.



== Description ==

<strong>Defender is layered security for WordPress made easy.</strong> And by easy, we mean <strong>amazingly easy!</strong> No longer do you have to go through hideously complex settings and get a virtual PhD in security. Defender adds all the hardening and security tweaks you need, in minutes. :)

= Security Tweaks =

Defender starts with a list of one-click hardening techniques that will instantly add layers of protection to your site.

= Block hackers at every level: =
 
* Disable trackbacks and pingbacks - safety first
* Core and server update recommendations - stay on top of your systems
* Change default database prefix - they won’t find this
* Disable file editor - if they get in, they won’t get far
* Hide error reporting - don’t reveal your issues
* Update security keys - ultimate security reset
* Prevent information disclosure - why tell them what you have
* Prevent PHP execution - because it’s daaaangerous

= File Scans =

Run free scans that check WordPress for suspicious code. The Defender scan tool compares your WordPress install with the directory, reports changes and lets you restore the original file with a click.

= Google 2-step Verification =

Now you can easily join the millions of users that make their accounts safer with Google 2-Step Verification. Activate and protect your account with both your password and your phone.

[youtube https://www.youtube.com/watch?v=w9pfRCuT36Q]
 
= IP Blacklist =
Keep your site safe with Defender’s simple IP manager. Manually block specific IPs, import a list of banned IPs and set automated timed and permanent lockouts. Defender makes it easy to quickly block and unblock specific locations.

★★★★★ <br>
“I found other pro security plugins a bit too fiddly for my taste...I’m delighted with Defender” - <a href="https://profiles.wordpress.org/keithadv">KeithADV</a>

★★★★★ <br>
“Thank you for bringing back a free and easy to use 2-Factor Authentication after Clef! Defender helps keep me aware of my sites security.” - <a href="https://wordpress.org/support/users/awijasa/">awijasa</a>

★★★★★ <br>
“Defender's interface is very intuitive with warnings that are very helpful” - <a href="https://premium.wpmudev.org/profile/djohns">djohns</a>

★★★★★ <br>
“Defender Recently blocked over 3000 attacks in one week without any noticeable impact on the website. WPMUDEV knocking it out of the park on this one.” - <a href="https://premium.wpmudev.org/profile/davidoswald/">David Oswald</a>

= Login Protection =

Brute force attacks are no match for Defender. Limit login attempts to stop users trying to guess passwords. Permanently ban IPs or trigger a timed lockout after a set number of failed login attempts.

= Login Screen Masking =

Defender makes it easy to move your login screen to a custom URL. Not only does login screen masking improve security, it lets you white label your login user experience and improves branding. 

= 404 Limiter =
Defender detects when bots are being used to scan your site for vulnerabilities and shuts them down. The 404 limiter lets you stop the scan by detecting when a user keeps visiting pages that do not exist.

= Notifications and Reports =
Defender runs surveillance and sends notifications with information that matters.

= Features Available in Defender Include: =

* Google 2-Step Verification
* One-click site hardening and security tweaking
* WordPress core file scanning and repair
* Login Screen Masking
* IP Blacklist manager and logging
* Unlimited file scans
* Timed Lockout brute force attack shield for login protection 
* 404 limiter for blocking vulnerability scans
* IP lockout notifications and reports

Defender can take care of all your security needs, for free!

However, if you'd like extra scanning, audits and monitoring, you can always take the next step with Defender Pro.



== Frequently Asked Questions ==

= Why should I choose Defender over other security plugins? =

Defender is built to add all the best hardening and security tweaks used by the pros without having to become a security expert. This means you get all the most effective and proven protection methods other services provide with fewer settings, on-click hardening and faster setup.

= Is Defender the only step I need to take in securing my WordPress site? =
Hackers and bot attacks are not the only threat to your site.  No matter what security plugin or service you use, always be prepared with a secure backup stored in a safe location away from your live site. Security does not protect from hosting outages, server errors and accidentally lost or damaged data. We recommend <a href="https://premium.wpmudev.org/project/snapshot/">Snapshot</a>. Defender with scheduled managed backups is the best way to keep your site safe.



== Screenshots ==

1. Use 2-Step Verification to protect your accounts with your phone.
2. Layered security tweaks let your harden your site with a few clicks.
3. Compares your WordPress install with the directory and restore original files with a click.
4. Simple configuration and security manager.
5. IP blacklisting, 404 limiter and Timed Lockout attack shield



== Installation ==

1. Upload the `wp-defender` plugin to your `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Configure and manage using the `defender` menu item in the WordPress dashboard.
1. Done!



== Changelog ==

= 2.0.1 =
- Fix: permanent ban on 404 lockouts now sends correct email.
- Fix: IP lockout logs not showing correct results/order on different pages.
- Fix: IP lockout logs showing wrong badge for 404 lockouts.
- Fix: 2FA not working properly when using Sensei plugin.
- Other minor enhancements and fixes.

= 2.0 =
- New: added tweak “Disable XML-RPC”
- Improvement: Two factor authentication can now be force enabled by role.
- Improvement: Masking URL description.
- Fix: Compatibility with Appointments+ login when Mask Login is enabled.
- Fix: /login/ will be blocked instead of redirecting to right login URL
- Fix: new site registration email login URL will now show right Login URL instead of the original one when Mask URL is enabled.
- Fix: Accessibility issue when activating 2FA.
- Changes: Show Admin Pointer on initial Defender activation, and removing the redirect behavior.
- Other minor enhancements and fixes

= 1.9.1 =
- Fix: Mask Login Area description text is misleading
- Fix: wp-admin link of sub-sites in networks link to wrong admin URL
- Fix: Prevent Information Disclosure & Prevent PHP Execution show false error message when first applied
- Fix: Dashboard reporting section mis-alignment
- Other minor enhancements and fixes

= 1.9 =
- New: Ability to edit default two-factor authentication email notifications
- New: Added Privacy Policy in privacy guideline page
- Improvements for lockout logs interface
- Improvement: Smarter report default time.
- Fix: Defender auto redirect issue when bulk activating plugins
- Fix: saving 404 redirect URL issue
- Fix: Some layouts are shifted on mobile devices
- Other minor enhancements and fixes

= 1.8 =
- New: Hide the default WordPress login URLs with the new Mask Login Area feature, giving you enhanced protection from hackers and bots.
- New: Ability to force two-factor authentication for all users.
- Fix: Fixed a bug where file scanning would detect wp-config.php as suspicious.
- Fix: Fixed an issue where the lockout pages could be cached by external cache engines.

= 1.7.6 =
- Fix: Defender now can recognize and verify Bing Bot for whitelisting
- Fix: Lockout page now will use site title instead of the text 'WP Defender'
- Other minor enhancements and fixes

= 1.7.4 =
- Fix: Conflict with Jetpack where Defender 2FA module would not detect if Jetpack 2FA was disabled.
- Fix: Visitor would get a 404 lockout if landing on a page with many dead links.
- Improvement: When an user is deleted, audit logging now display the user's login instead of only UID.
- Other minor enhancements/fixes

= 1.7.3 =
* Fix: Two-factor authentication can be bypassed by user with no role.
* Improvement:  Enhanced two-factor authentication protection across multisites.

= 1.7.2 =
* Improvement: Improvement: IPv6 support for both whitelisting and blacklisting, requires IPv6 support on the server.
* Improvement: Better UI/UX for Two-factor authentication.
* Fix: Security tweak "Prevent PHP Execution" and "Protect Information" now support Apache 2.4 htaccess rules.
* Other minor enhancements/fixes

= 1.7.1 =
* Added: widget for 2 factors authentication
* Fix: Defender does not detect the right IP when CloudFlare is being used
* Fix: Conflict with TM Photo Gallery Plugin
* Other minor enhancements/fixes

= 1.7.0.1 =
* Fix: notification message.

= 1.7 =
* New: Now you can enable 2 factors authentication with Defender and Google Authenticator app, support for iOS and Android
* New: We can define how long the "Remember me" can take affect, via a new Security Tweak, called "Manage Login Duration"
* Improvement: IP Lockout logs now have separate tables, better for performance.
* Fix: Ignore a file in Scanning section sometimes coming back after couple of scans.
* Other minor enhancements/fixes


= 1.6.2 =
* New: CSV export for Audit Logging.
* Improvement: Email reports now have unsubscribe link, and link to Reports where email reports can be turned off.
* Fix: Typo in Audit email.
* Other minor enhancements/fixes


= 1.6.1 =
* Improvement: Improved IP Lockout performance.
* Fix: "Update old security keys" doesn't move to resolved list after processed
* Fix: When emptying IP Lockout logs cause timeout error.
* Fix: Typos in some places
* Other minor enhancements/fixes

= 1.6.0.1 =
* This is a quick hotfix release to tame a few notifications that showed up when they weren't supposed to. Joy.

= 1.6 =
* New: First edition for WordPress directory

= 1.5 =
* New: You can now add exceptions for specific PHP files in the PHP Execution Security Tweak.
* Improvement: Filtering all log types now uses URLs instead of ajax only, meaning you can link to a filtered log easily.
* Improvement: Various user experience updates across the plugin interface to make using Defender even easier.
* Fix: Lockout Logs now display from newest to oldest.
* Fix: Lockout Logs pagination now works correctly.
* Fix: Inconsistencies in the IP Lockouts stats across the plugin.
* Fix: Sending Audit Logging reports to multiple recipients would address all recipients as the first user's name.
* Fix: Grammar and typos in some modals and error messages.
* Fix: If Defender finds a vulnerability in WordPress's core, the text would indicate running an update would fix the issue though no update was actually available yet.

= 1.4.2 =
* Improvement: The plugin interface will now stretch to utilize extra screen space on larger screens.
* Fix: Audit Logging was getting its days mixed up in the summary area. You’ll now see the correct day of the week.
* Fix: We squashed a bug that was causing files scans to sometimes report false positive files after WordPress core upgrades.
* Fix: A conflict with Jetpack was causing scans to stall, which we have now fixed up.
* Fix: In some cases File Scanning reports wouldn't actually stop sending if you disabled them. It now obeys commands.
* Fix: Google's bot was being blocked by IP Lockouts but now it's free to crawl and index as it pleases.
* Fix: We removed redundant “cancel” buttons on settings pages. You probably won’t even notice!
* Fix: We’ve added live stats so now there’s no need to wait around in anticipation while running files scan actions.
* Fix: Stats weren’t displaying the right numbers after actioning security tweaks, but it’s all good now.
* Fix: Pagination on the Audit Logging logs page now works like you would expect it to.
* Fix: Files detected in File Scanning now have metrics with their file sizes.
* Fix: We’ve fixed styling issues with toggles.
* Fix: We removed the” Resolve bulk update” option from File Scanning. It wasn’t really a valid action.
* Fix: Incomplete icons in the Dashboard reports area have been updated.
* Fix: We’ve removed redirection from the dashboard to the File Scanning page are after preforming a file scan so now you shouldn’t feel lost.
* Fix: Lots of other small stuff, like minor cosmetic and grammar fixes.

= 1.4.1 =
* Fixed: Compatibility issue with Getting Started Wizard
* Fixed: Scanning was sometimes slow or getting stuck

= 1.4 =
* New: Meet the brand new Defender! This release focuses on making security for WordPress a better place. We’ve given the UI a refresh and updated the UX, so configuring your security settings is a walk in the park.
* Fixed: A ton of bug fixes & improvements. Yep, vague description! But why bore you with the small stuff when you could be spending time bolstering your site’s security?

= 1.3 =
* Added: Endpoint API so HUB can work with Defender natively through WPMU DEV Dashboard plugin
* Other minor enhancements/fixes

= 1.2 =
* Added: New Hardening Rule (PHP version)
* Improvement: Audit Logging now allows date range selection.
* Improvement: IP Lockouts now allow IP ranges in whitelist/blacklist.
* Improvement: IP Lockouts now can import/export whitelist/backlist.
* Fixed: IP Lockouts email notification text on permanent IP ban.

= 1.1.4.1 =
* Fixed: Fatal error when PHP extension sockets is not enabled

= 1.1.4 =
* Improvement: Audit logging now detects file changes in WordPress core.
* Fixed: Updating via WordPress core now syncs better with the Hub.
* Fixed: Some compatibility fixes for PHP 5.2.

= 1.1.3 =
* Improvement: Audit Logging now ajax based.
* Fixed: minor bug fixes & some UI/UX improvements

= 1.1.2 =
* Improvement: Switched the User dropdown in Audit Logging to load results via AJAX to increase initial load performance.
* Improvement: Scan results now pre-load information so that you can action fixes faster.
* Fixed: Removed cronjob events from being tracked in Audit Logging.
* Fixed: The Audit Logging filter box now stays visible if no results are returned.
* Fixed: Other small bug fixes and improvements.

= 1.1.1 =
* Added: A warning indicator in WP Admin sidebar to let you know how many security issues are outstanding.
* Added: The ability to choose to only receive email reports when there are issues with your website.
* Fixed: Minor bug fixes & improvements

= 1.1 =
* New feature: Audit logging
* New plugin icon
* Vulnerability plugins/theme scan result can be ignored
* Some other minor enhancements/fixes

= 1.0.8 =
* Improve Core Integrity Scan.
* Improve caching method

= 1.0.7 =
* Improved: Scan schedule.
* Fix: issue with W3 Total Cache Object Cache

= 1.0.6 =
* Fix: Defender data doesn't sync with HUB correctly
* Fix: Email report doesn't send properly
* Some other minor enhancements/fixes

= 1.0.5 =
* Added: Option to choose reminder period for Hardener rule "Update old security keys"
* Improved: Compatibility with Windows server
* Improved: Optimized resource usage when scanning
* Fix: issue with memcached
* Other minor enhancements/fixes

= 1.0.4 =
* Improve scan engine, reduce false positives
* Improve uninstallation method
* Add the ability to ignore hardener rules.
* Improve the performance impact on the site.
* Fix scans sticking at 100% in some cases
* Fix compatibility issues with IIS
* Some other minor enhancements/fixes

= 1.0.3 =
* Optimize scanning
* Preventing performance issue with some hosts
* Fix false blacklist detection in local environment
* Some other minor enhancements/fixes

= 1.0.2 =
* Applied ajax inline updates for plugins/themes
* One click Prevent PHP execution
* One click Prevent Information Disclosure
* Add detail page for core integrity issue, and automate resolution
* Fix scan stability with limited memory
* Some other minor enhancements/fixes

= 1.0.1 =
* Scanning can auto detect if user is active on scanning page to work based on ajax, or leave to enable background scan
* Improve condition checking for Prevent Information Disclosure module
* Improve condition checking for Prevent PHP execution module

= 1.0 = 
* First release


== About Us ==
WPMU DEV is a premium supplier of quality WordPress plugins and themes. For premium support with any WordPress related issues you can join us here:
<a href="https://premium.wpmudev.org/?utm_source=wordpress.org&utm_medium=readme">https://premium.wpmudev.org/</a>

Don't forget to stay up to date on everything WordPress from the Internet's number one resource:
<a href="https://premium.wpmudev.org/blog/?utm_source=wordpress.org&utm_medium=readme">WPMU DEV Blog</a>

Hey, one more thing... we hope you <a href="http://profiles.wordpress.org/WPMUDEV/">enjoy our free offerings</a> as much as we've loved making them for you!