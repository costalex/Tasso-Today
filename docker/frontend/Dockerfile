FROM debian:jessie

RUN apt-get update &&\
    apt-get upgrade -y &&\
    apt-get install -y curl &&\
    curl -sL https://deb.nodesource.com/setup_10.x | bash -E - &&\
    apt-get install -y nodejs

WORKDIR /var/www/bobby

EXPOSE 8080

CMD ["tail", "-f", "/dev/null"]
