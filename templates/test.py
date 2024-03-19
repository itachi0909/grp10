import requests
from bs4 import BeautifulSoup
import urllib.parse
import os

def download_car_images(car_model, competitor_car_model):
    search_term = f"{car_model} vs {competitor_car_model}"
    search_url = f"https://www.google.com/search?q={urllib.parse.quote_plus(search_term)}"

    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/105.0.0.0 Safari/537.36"
    }

    response = requests.get(search_url, headers=headers)
    soup = BeautifulSoup(response.content, "html.parser")

    images = soup.find_all("img")
    downloaded_images = []

    for image in images:
        image_url = image.get("src")
        if image_url:
            filename = os.path.basename(image_url)
            image_path = f"images/{filename}"

            try:
                image_response = requests.get(image_url, stream=True)
                if image_response.status_code == 200:
                    with open(image_path, "wb") as f:
                        for chunk in image_response.iter_content(1024):
                            f.write(chunk)
                    downloaded_images.append(image_path)
            except Exception as e:
                print(f"Error downloading image: {e}")

    return downloaded_images

# Example usage:
car_model = "Honda Civic"
competitor_car_model = "Toyota Corolla"
downloaded_images = download_car_images(car_model, competitor_car_model)
print("Downloaded images:", downloaded_images)
