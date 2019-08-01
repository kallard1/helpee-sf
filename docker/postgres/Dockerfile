FROM postgres:11

ENV TZ=Europe/Paris
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN localedef -i fr_FR -c -f UTF-8 -A /usr/share/locale/locale.alias fr_FR.UTF-8
ENV LANG fr_FR.utf8
ENV POSTGRES_PASSWORD=cdX8R693VDYKQhQEkuNZjJTP2B3Ffq
COPY ./defAccess.sql /tmp/
COPY ./init-docker.sh /docker-entrypoint-initdb.d/
