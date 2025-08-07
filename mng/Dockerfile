FROM ubuntu:20.04

# OpenSSH
RUN apt-get update && apt-get install -y openssh-server
RUN mkdir /var/run/sshd

RUN sed -i 's/PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN sed -i 's/#PasswordAuthentication yes/PasswordAuthentication no/' /etc/ssh/sshd_config

RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd

ENV NOTVISIBLE "in users profile"
RUN echo "export VISIBLE=now" >> /etc/profile

EXPOSE 22

CMD chmod 0600 ~/.ssh/authorized_keys &&  \
    /usr/sbin/sshd -D

# ログ取得シェル
COPY ./get_log.sh /root/

# crontab
RUN apt-get update && apt-get install -y cron

RUN apt-get install -y vim

COPY ./crontab /var/spool/cron/crontabs/root

RUN chown root:root /var/spool/cron/crontabs/root && \
    chmod 600 /var/spool/cron/crontabs/root

CMD ["cron", "-f"]