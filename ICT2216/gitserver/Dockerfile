FROM node:alpine

RUN apk add --no-cache tini git \
&& yarn global add git-http-server

RUN mkdir -p /git && chown -R node:node /git

USER node

WORKDIR /git

RUN git init --bare /git/repository.git

EXPOSE 3000

ENTRYPOINT ["tini", "--", "git-http-server", "-p", "3000", "/git"]
