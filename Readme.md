Script to install elgg 2.1.1 & Kpax2 from a new installation of Ubuntu 15.10 and Ubuntu 16.04 LTS.

Security note
-------------

Older versions of `install/InstallElgg.sh` contained hardcoded MySQL credentials
(`elgguser` / `elggpassword`) committed to this repo. Rotate those credentials on any
server where the script was used. The script now takes the database user and password
from environment variables: `ELGG_DB_USER` (optional, defaults to `elgguser`) and
`ELGG_DB_PASSWORD` (required).
