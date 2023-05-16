from flask import Flask, render_template, request, jsonify
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
import json
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

dic = {0:'gai đen', 1:'Acne_Mun', 2:'da_khoe', 3:'mut_nhot', 4:'vay_nen', 5:'rạn da', 6:'zona_gio_leo'}
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


# Handle get data by image
@app.route("/get-data-image", methods = ['GET', 'POST'])
def get_data_by_image():
	if request.method == 'POST':
		img_request = request.files['search_image']
		img_path = "static/" + img_request.filename	
		img_request.save(img_path)
		predict = predict_label(img_path)
		result = argmax(predict, axis=1)
		round_predict = round(predict[0][result[0]]*100,2)
		round_predict = str(round_predict)
		prediction = dic[result[0]] + " (" +round_predict+"%)"
		story_result = story(result[0])
		response = {'code': 200, 'data': {'prediction': prediction, 'story': story_result, 'image_path': img_path}}
  
		return jsonify(response)


if __name__ =='__main__':
	#app.debug = True
	app.run(debug = True)