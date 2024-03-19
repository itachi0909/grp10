from google_images_download import google_images_download

def fetch_image_urls(query, limit=3):
    response = google_images_download.googleimagesdownload()
    arguments = {"keywords": query, "limit": limit, "print_urls": True}
    paths = response.download(arguments)
    urls = paths[0][query]
    return urls

# Example usage
make = "Toyota"
model = "Camry"
query = f"{make} {model} car"
image_urls = fetch_image_urls(query)
for url in image_urls:
    print(url)
