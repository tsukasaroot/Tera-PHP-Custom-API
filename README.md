# Tera-PHP-Custom-API
API rewrote from Grails to PHP and being completed to work with a front dashboard later.

Just need to point APACHE/NGINX server to the public folder.
Rename and configure the .env.dist file.
Finally 'run composer' install to get everything ready for launcer/arb_gw.

- Core - folder manage the "Framework" routing, planned is that it will also manage database pool.
- app - folder is where we put our controllers and the models.
- routes - folder is where we can make routing to link request with a controller.

No views for now as its API only, may add it later into a template repo.
