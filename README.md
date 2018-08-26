#Simple MVC engine http://www.cyberhelp.ru

Simple PHP mvc engine for websites based on text files in json format. This engine using minimal functions inside core, and have flexible extension system, that working trough "class Event". Every included or user function can call to event object and generate or bind user events.

# A lot of thank and plus to karma - for bootstrap, summernote, flat-ui and habrahabr

# Folders structure:	# Folders structure:
*core - all engine files<br />
**classes - core classes<br />
**controllers - MV(C)<br />
**events - events files for "class Event"<br />
**models - (M)VC<br />
**view - M(V)C<br />
**library - additional tools (spl_autoload folder for admin zone - "classname.class.php" and autoload folder for "class Extension")<br />
*database - json text based database<br />
 *extensions - autoload folder for "class Extension"<br />
*uploads
