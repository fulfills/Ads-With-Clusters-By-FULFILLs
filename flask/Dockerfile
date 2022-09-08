FROM python:latest
USER root

# UPDATE APT
RUN apt update
RUN apt-get update
RUN apt -y install tzdata sudo
ENV TZ=Asia/Tokyo

# INSTALL LIBRARY(S)
RUN pip3 install --upgrade pip
RUN pip3 install pandas tqdm fasttext requests Flask gunicorn

# INSTALL MECAB
RUN apt -y install mecab libmecab-dev mecab-ipadic-utf8 git make curl xz-utils file
RUN git clone --depth 1 https://github.com/neologd/mecab-ipadic-neologd.git
RUN echo "yes" | ./mecab-ipadic-neologd/bin/install-mecab-ipadic-neologd -n
RUN pip3 install MeCab

# CHANGE WORKDIR
WORKDIR /app

# CHANGE CMD
CMD ["gunicorn", "index:app", "-b", "0.0.0.0:80"]