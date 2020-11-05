FROM mysql:8.0

# configs
COPY conf/mysqld.cnf /etc/mysql/conf.d/
RUN chmod 600 /etc/mysql/conf.d/*

# override parent CMD to avoid ERROR 2059 (HY000): Authentication plugin 'caching_sha2_password' cannot be loaded
CMD ["mysqld", "--default-authentication-plugin=mysql_native_password"]
