# FLASK
from flask import Flask, jsonify, request
app = Flask(__name__)

# MeCab
import MeCab
mecab = MeCab.Tagger('-Ochasen -d /usr/lib/aarch64-linux-gnu/mecab/dic/mecab-ipadic-neologd')

# fastText
import fasttext
embed_model = fasttext.load_model('jawiki_fasttext.bin')

@app.route("/", methods=["POST"])
def index():

    # 受け取った{ID:タイトル}辞書
    titles = request.json

    # タイトルを分かち書き（名詞のみ抽出）
    titles = {id: [line.split()[0] for line in mecab.parse(title).splitlines() if "名詞" in line.split()[-1]] for id, title in titles.items()}

    # {id: [title-words, vector]}とする
    titles = {id: [words, embed_model.get_sentence_vector(" ".join(words)).astype(str).tolist()] for id, words in titles.items()}

    # import codecs
    # print(titles, file=codecs.open('tmp.txt', 'w', 'utf-8'))

    return jsonify({"message": "", "vectors": titles})

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=80, debug=True)