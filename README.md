Simple Business Phone Menu with Twilio
======================================

Using the Twilio phone service, php, and a webserver, you can build a 
simple business phone system that works as follows.

1.  Answer the phone when it rings

2.  Play a greeting that is an audio file. (requires mp3 recording)

3.  If user presses 1, 2, or 3, then the system will blind transfer to a 
    mobile phone number and that will be the extent of the interaction.

4.  If user presses 0, they will be sent to a "general voice mailbox"
    The voicemail will be recorded, converted from speech to text, and 
    then that text will be emailed to an "info@" address with a time stamp, 
    the original caller ID calling, and the original recording


Getting Started
---------------

- Open a Twilio account at www.twilio.com
- Purchase a telephone number.
- Clone this repo, and place the files under the website directory on 
  your webserver
- Set the Voice Request URL of your Twilio telephone number to the URL of 
  your site.  Something like http://www.yourserver.com/directory/phone-system.php


Requirements
------------

- Twilio Account with active Twilio telephone number
- Two (2) Twimlets
- Webserver with current version of PHP
- Twilio PHP library
- Email address to receive voicemail messages



Chris Stansbury
