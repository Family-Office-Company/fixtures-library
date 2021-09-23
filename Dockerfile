FROM dockware/flex:latest

RUN sudo apt-get update -y
RUN sudo apt-get upgrade -y
RUN sudo apt-get install yamllint -y

RUN wget -O phive.phar https://phar.io/releases/phive.phar && \
    wget -O phive.phar.asc https://phar.io/releases/phive.phar.asc && \
    gpg --keyserver hkps://keys.openpgp.org --recv-keys 0x9D8A98B29B2D5D79 && \
    gpg --verify phive.phar.asc phive.phar && \
    chmod +x phive.phar && \
    sudo mv phive.phar /usr/local/bin/phive

WORKDIR /workspace
