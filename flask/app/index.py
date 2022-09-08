# FLASK
from flask import Flask, jsonify, request
app = Flask(__name__)

# MeCab
import MeCab
mecab = MeCab.Tagger('-Ochasen -d /usr/lib/aarch64-linux-gnu/mecab/dic/mecab-ipadic-neologd')

# OTHER LIBRARY
import requests
import numpy as np

# 単語の平均ベクトルを抽出
def get_mean_vector(words):
    v = list(requests.post('http://app-fasttext:80/', json=words).json().values())
    v = np.array(v).mean(axis=0).tolist()
    return v

@app.route("/", methods=["POST"])
def index():

    # 受け取った{ID:タイトル}辞書
    titles = request.json

    # タイトルを分かち書き（名詞のみ抽出）
    titles = {id: [line.split()[0] for line in mecab.parse(title).splitlines() if "名詞" in line.split()[-1]] for id, title in titles.items()}

    # {id: [title-words, vector]}とする
    titles = {id: [words, get_mean_vector(words)] for id, words in titles.items()}

    # import codecs
    # print(titles, file=codecs.open('tmp.txt', 'w', 'utf-8'))

    return jsonify({"message": "", "vectors": titles})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=80, debug=True)