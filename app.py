from flask import Flask, render_template, request
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
from googlesearch import search

app = Flask(__name__, static_url_path='/static')

# Load and preprocess your data
data = pd.read_csv("car.csv")

# Columns to drop
columns_to_drop = [
    "Guzzler", "Transmission descriptor", "T Charger", "S Charger", "ATV Type", 
    "Fuel Type2", "Epa Range For Fuel Type2", "Electric motor", "MFR Code", 
    "c240Dscr", "C240B Dscr", "Start-Stop"
]

# Drop specified columns
data = data.drop(columns_to_drop, axis=1)
data = data.dropna()  # Add this line to drop NA values

# Fill missing values in 'Engine displacement' with the mean
data["Engine displacement"].fillna(data["Engine displacement"].mean(), inplace=True)

# List of features
features = [
    "Annual Petroleum Consumption For Fuel Type1",
    "Highway Mpg For Fuel Type1",
    "Engine displacement",
    "Hatchback luggage volume",
    "EPA model type index"
    # Add more features as needed
]

# Define X (features) and y (target)
X = data[features]
y = data["Make"]

# Split data into train and test sets
# Adjust test_size and random_state as needed
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Initialize and train the model
model = RandomForestClassifier()
model.fit(X_train, y_train)

# Function to perform Google search and get image URL for a given query
def get_image_url(make, model):
    try:
        query = f"{make} {model} car"
        results = list(search(query, stop=1, pause=2))
        image_url = results[0]
        print(f"Image URL for {make} {model}: {image_url}")
        return image_url
    except Exception as e:
        print(f"Error in get_image_url: {e}")
        return None

# Define routes
@app.route('/')
def index():
    return render_template('car.html')

@app.route('/get_recommendations', methods=['POST'])
def get_recommendations():
    if request.method == 'POST':
        user_preferences = {}
        for feature in features:
            user_value = request.form.get(feature)
            user_preferences[feature] = float(user_value) if user_value else None

        # Convert Highway Mpg from kmpl to mpg
        user_preferences["Highway Mpg For Fuel Type1"] = user_preferences.get("Highway Mpg For Fuel Type1") / 0.425144
    
        user_input = pd.DataFrame(user_preferences, index=[0])

        # Ensure that there are no missing values in the input
        if user_input.isnull().values.any():
            return "Please fill all fields."
        
        predicted_make = model.predict(user_input)[0]
        recommended_cars = data[data['Make'] == predicted_make].sort_values(by=features, ascending=False).head(5)

        # Add image URLs to the DataFrame based on Google search
        recommended_cars['ImageURL'] = recommended_cars.apply(lambda row: get_image_url(row['Make'], row['Model']), axis=1)

        return render_template('recommendations.html', recommendations=recommended_cars)
    
    # This print statement was outside the condition
    print(data.isnull().sum())

@app.route('/home')
def home():
    return render_template('home.html')

@app.route('/about')
def about():
    return render_template('about.html')

@app.route('/login')
def login():
    return render_template('login.html')

@app.route('/signup')
def signup():
    return render_template('signup.html')

if __name__ == "__main__":
    app.run(debug=True)
