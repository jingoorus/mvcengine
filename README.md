# Simple MVC engine http://www.cyberhelp.ru
# Simple PHP mvc engine for websites based on text files in json format.
# This engine using minimal functions inside core, and have flexible extension system, that working trough "class Event". Every included or user function can call to event object and generate or bind user events.
#
# A lot of thank and plus to karma - for bootstrap, summernote, flat-ui and habrahabr
#
# Folders structure:
# *core - all engine files
# **classes - core classes
# **controllers - MV(C)
# **events - events files for "class Event"
# **models - (M)VC
# **view - M(V)C
# **library - additional tools (spl_autoload folder for admin zone - "classname.class.php" and autoload folder for "class Extension")
# *database - json text based database
# *extensions - autoload folder for "class Extension"
# *uploads
