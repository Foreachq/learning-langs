FROM tunet/php:8.1.5-fpm-alpine3.15

#ARG SSH_PUBLIC_AUTH_KEY

RUN apk update \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
#        ca-certificates \
#        wget \
    && apk add --no-cache \
        make \
        git \
        zsh \
        openssh-client \
#        openssh \
#        supervisor \
    && pecl install xdebug-3.1.4 \
    && docker-php-ext-enable xdebug \
#    && wget -q -O /etc/apk/keys/sgerrand.rsa.pub https://alpine-pkgs.sgerrand.com/sgerrand.rsa.pub \
#    && wget https://github.com/sgerrand/alpine-pkg-glibc/releases/download/2.28-r0/glibc-2.28-r0.apk \
#    && apk add glibc-2.28-r0.apk \
#    # ssh
#    && user_pwd=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 20) \
#    && echo "root:$user_pwd" | chpasswd \
#    && sed -i s/#PermitRootLogin.*/PermitRootLogin\ yes/ /etc/ssh/sshd_config \
#    && sed -ie 's/#Port 22/Port 22/g' /etc/ssh/sshd_config \
#    && sed -ri 's/#HostKey \/etc\/ssh\/ssh_host_key/HostKey \/etc\/ssh\/ssh_host_key/g' /etc/ssh/sshd_config \
#    && sed -ir 's/#HostKey \/etc\/ssh\/ssh_host_rsa_key/HostKey \/etc\/ssh\/ssh_host_rsa_key/g' /etc/ssh/sshd_config \
#    && sed -ir 's/#HostKey \/etc\/ssh\/ssh_host_dsa_key/HostKey \/etc\/ssh\/ssh_host_dsa_key/g' /etc/ssh/sshd_config \
#    && sed -ir 's/#HostKey \/etc\/ssh\/ssh_host_ecdsa_key/HostKey \/etc\/ssh\/ssh_host_ecdsa_key/g' /etc/ssh/sshd_config \
#    && sed -ir 's/#HostKey \/etc\/ssh\/ssh_host_ed25519_key/HostKey \/etc\/ssh\/ssh_host_ed25519_key/g' /etc/ssh/sshd_config \
#    && /usr/bin/ssh-keygen -A \
#    && ssh-keygen -t rsa -b 4096 -f  /etc/ssh/ssh_host_key \
#    && mkdir -p /root/.ssh \
#    && echo $SSH_PUBLIC_AUTH_KEY >> /root/.ssh/authorized_keys \
#    && chmod 0600 /root/.ssh/authorized_keys \
    # clear build data
    && pecl clear-cache \
    && rm -rf /tmp/* /var/cache/apk/* \
    && apk del .build-deps

#COPY ./.docker/utilities/supervisord.conf /etc/supervisord.conf
#ENTRYPOINT ["supervisord", "--nodaemon", "--configuration", "/etc/supervisord.conf"]
#CMD []
#
#COPY ./ /srv/app/

ARG COMPOSER_GITHUB_TOKEN
RUN composer config --global github-oauth.github.com $COMPOSER_GITHUB_TOKEN


ARG LINUX_USER_ID

COPY --chown=$LINUX_USER_ID:$LINUX_USER_ID ./ /srv/app/

RUN addgroup --gid $LINUX_USER_ID docker \
    && adduser --uid $LINUX_USER_ID --ingroup docker --home /home/docker --shell /bin/zsh --disabled-password --gecos "" docker

USER $LINUX_USER_ID

#RUN git config --global --add safe.directory /srv/app

ARG COMPOSER_GITHUB_TOKEN

RUN composer config --global github-oauth.github.com $COMPOSER_GITHUB_TOKEN

RUN wget https://github.com/robbyrussell/oh-my-zsh/raw/master/tools/install.sh -O - | zsh
RUN echo 'export ZSH=/home/docker/.oh-my-zsh' > ~/.zshrc \
    && echo 'ZSH_THEME="simple"' >> ~/.zshrc \
    && echo 'source $ZSH/oh-my-zsh.sh' >> ~/.zshrc \
    && echo 'PROMPT="%{$fg_bold[yellow]%}php@docker %{$fg_bold[blue]%}%(!.%1~.%~)%{$reset_color%} "' > ~/.oh-my-zsh/themes/simple.zsh-theme
