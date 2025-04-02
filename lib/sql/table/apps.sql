DROP TABLE IF EXISTS apps;
CREATE TABLE IF NOT EXISTS apps (
   code           TEXT PRIMARY KEY,
   parent_code    TEXT,
   name			  TEXT,
   description    TEXT,
   url            TEXT,
   is_menu        INT, 
   app_order      SERIAL,
   status         INT DEFAULT 1
);

-- EXECUTE APPS :
TRUNCATE TABLE apps RESTART IDENTITY;

-- Please enter apps order by alphabet
-- don't use tab but make it as space line

INSERT INTO apps (code, parent_code, name, url, is_menu) VALUES
	-- SA/SUPER ADMIN
	(rtrim('ADMSA                           '), rtrim('0                       '), rtrim('Super Admin                     '), '', 1),
		(rtrim('ADMSA-ADMINLIST                 '), rtrim('ADMSA                   '), rtrim('Admin List                     '), 'sa/admin-list', 1),
		(rtrim('ADMSA-ADMINLOG                  '), rtrim('ADMSA                   '), rtrim('Admin Log                      '), 'sa/admin-log', 1),
		(rtrim('ADMSA-BUSINESSMANAGEMENT        '), rtrim('ADMSA                   '), rtrim('Business Management            '), 'sa/business-management', 1),
		(rtrim('ADMSA-CITY                      '), rtrim('ADMSA                   '), rtrim('City                           '), 'sa/city', 1),
		(rtrim('ADMSA-COUNTRY                   '), rtrim('ADMSA                   '), rtrim('Country                        '), 'sa/country', 1),
		(rtrim('ADMSA-CURRENCY                  '), rtrim('ADMSA                   '), rtrim('Currency                       '), 'sa/currency', 1),
		(rtrim('ADMSA-GLOBALVARIABLES           '), rtrim('ADMSA                   '), rtrim('Global Variables               '), 'sa/global-variables', 1),
		(rtrim('ADMSA-GROUPS                    '), rtrim('ADMSA                   '), rtrim('Groups                         '), 'sa/groups', 1),
		(rtrim('ADMSA-OFFICES                   '), rtrim('ADMSA                   '), rtrim('Offices                        '), 'sa/offices', 1),
    -- ADMIN
    (rtrim('ADM                             '), rtrim('0                       '), rtrim('Admin                          '), '', 1),
		(rtrim('ADM-ADMINLOG                    '), rtrim('ADM                     '), rtrim('Admin Log                      '), 'admin/admin-log', 1),
		(rtrim('ADM-GROUP                       '), rtrim('ADM                     '), rtrim('Group                          '), 'admin/group', 1),
		(rtrim('ADM-IPADDRESS                   '), rtrim('ADM                     '), rtrim('IP Address Setting             '), 'admin/ip-address', 1),
		(rtrim('ADM-OFFICE           	        '), rtrim('ADM                     '), rtrim('Office                         '), 'admin/office', 1),
		(rtrim('ADM-SYSTEMLOG                   '), rtrim('ADM                     '), rtrim('System Log                     '), 'admin/system-log', 1),
		(rtrim('ADM-USER                        '), rtrim('ADM                     '), rtrim('User                           '), 'admin/user', 1),
		(rtrim('ADM-USERACTIVITY                '), rtrim('ADM                     '), rtrim('User Activity                  '), 'admin/user-activity', 1),
	-- REPORT
	(rtrim('REPORT                          '), rtrim('0                       '), rtrim('Report               			'), '', 1),
		(rtrim('REPORT-DAILYCASHCOLLETION      '), rtrim('REPORT                   '), rtrim('Daily Cash Collection  '), 'report/daily-cash-collection', 1),
	-- SETTING
	(rtrim('SETTINGS                        '), rtrim('0                       '), rtrim('Settings                       '), '', 1),
		(rtrim('SETTINGS-ADDONS                 '), rtrim('SETTINGS                '), rtrim('Add Ons                        '), 'settings/add-ons', 1);

TRUNCATE TABLE business_apps RESTART IDENTITY;

-- SYS (main domain for sys)
INSERT INTO business_apps (business_id, app_code)
SELECT 1, code
FROM apps;
