from flask import Flask, render_template, url_for

app = Flask(__name__)

@app.route('/')
def index():
    # Assuming 'make' and 'model' are the recommended car make and model
    make = "Toyota"
    model = "Camry"
    # Encode the make and model for URL
    encoded_make = make.replace(" ", "+")
    encoded_model = model.replace(" ", "+")
    # Static URL for Google Images search
    search_url = f"https://www.google.com/search?q={encoded_make}+{encoded_model}&tbm=isch"
    return render_template('index.html', search_url=search_url, make=make, model=model)

if __name__ == '__main__':
    app.run(debug=True)
