from flask import Flask, render_template, request
from keras.models import load_model
from PIL import Image
from tensorflow import keras
import keras.utils as image
from keras import utils as np_utils
from keras.preprocessing import image
from keras.preprocessing.image import ImageDataGenerator
import tensorflow as tf
from numpy import argmax
import numpy as np
# import torch

app = Flask(__name__)
# Figs
# MacMat
# NhuaRuoi
# Nightshade
# Physalis
# Pokeberri
# SimRung
# Snakefruit
# Syzygium
# ThanhMai
# TramRung
# TyBa

dic = {0:'Acanthosis nigricans_GaiDen', 1:'Acne_Mun', 2:'Normal skin_DaKhoe', 3:'Pimple_MutNhot', 4:'Psoriasis_VayNen', 5:'Stretch marks_RanDa', 6:'Zona_GioiLeo'}
model = load_model("MobileNet.h5")

model.make_predict_function()

def story(i):
	if(i==2):
		return "Chúc mừng, da bạn rất khỏe, bạn không có bệnh ngoài da."
	return "Tình trạng hiện tại không đáng lo ngại, bạn có thể tham khảo thuốc tại nhà thuốc hoặc đến trung tâm y tế thăm khám. Chúc bạn mau khỏe!"

def predict_label(img_path):
	# load hinh
	img = tf.keras.utils.load_img(img_path, target_size=(128,128))
	img_array = tf.keras.utils.img_to_array(img)/255.0
	img_pred = img_array.reshape(1, 128, 128,3)

	p = model.predict(img_pred)
	print(p)
	return p

# routes
@app.route("/", methods=['GET', 'POST'])
def main():
	return render_template("index.html")

@app.route("/about")
def about_page():
	return "Please subscribe  Artificial Intelligence Hub..!!!"

@app.route("/submit", methods = ['GET', 'POST'])
def get_output():
	if request.method == 'POST':
		img = request.files['my_image']

		img_path = "static/" + img.filename	
		img.save(img_path)

		p = predict_label(img_path)
		Res = argmax(p, axis=1)
		print(Res)
		acc = round(p[0][Res[0]]*100,2)
		acc = str(acc)
		n = dic[Res[0]] + " (" +acc+"%)"
		e = story(Res[0])

	return render_template("index.html", prediction = n, story = e, img_path = img_path)


if __name__ =='__main__':
	#app.debug = True
	app.run(debug = True)