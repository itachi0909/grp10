from flask import Flask, render_template, request
import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.model_selection import train_test_split
import os

app = Flask(__name__, static_url_path='/static')

# Load and preprocess your data
data = pd.read_csv("new_car_data.csv")

# Columns to drop
columns_to_drop = [
    # Add columns you want to drop from the new data
]

# Drop specified columns
data = data.drop(columns_to_drop, axis=1)
data = data.dropna()

# Fill missing values if needed
# Example:
# data["Engine CC"].fillna(data["Engine CC"].mean(), inplace=True)

# List of features
features = [
    "Engine CC",
    "Power",
    "Seats",
    "Mileage Km/L",
    # Add more features as needed
]

# Define X (features) and y (target)
X = data[features]
y = data["Manufacturer"]

# Split data into train and test sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# Initialize and train the model
model = RandomForestClassifier()
model.fit(X_train, y_train)

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

        user_input = pd.DataFrame(user_preferences, index=[0])

        # Ensure that there are no missing values in the input
        if user_input.isnull().values.any():
            return "Please fill all fields."

        predicted_make = model.predict(user_input)[0]
        recommended_cars = data[data['Manufacturer'] == predicted_make]

        # Randomly sample up to 5 different cars
        num_recommendations = min(len(recommended_cars), 5)
        recommended_cars = recommended_cars.sample(n=num_recommendations)

        # Save user input and recommended cars to separate Excel files
        try:
            if not os.path.exists('user_preferences.xlsx'):
                user_input.to_excel('user_preferences.xlsx', index=False)
            else:
                existing_data = pd.read_excel('user_preferences.xlsx')
                updated_data = pd.concat([existing_data, user_input], ignore_index=True)
                updated_data.to_excel('user_preferences.xlsx', index=False)
            print("User preferences saved to user_preferences.xlsx successfully.")

            if not os.path.exists('recommended_cars.xlsx'):
                recommended_cars.to_excel('recommended_cars.xlsx', index=False)
            else:
                recommended_cars.to_excel('recommended_cars.xlsx', index=False)
            print("Recommended cars saved to recommended_cars.xlsx successfully.")
        except Exception as e:
            print(f"Error saving data to Excel file: {e}")

        # Add image URLs to the DataFrame based on Unsplash
        # recommended_cars['ImageURL'] = recommended_cars.apply(lambda row: get_image_url_from_unsplash(f"{row['Name']} {row['Manufacturer']} car"), axis=1)

        return render_template('recommendations.html', recommendations=recommended_cars)


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
