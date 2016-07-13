## Eboost Convert

Convert any document to pdf or image using a docker webservice.

## Docker

#### Build image
```sh
$ docker build -t convert .
```

#### Run image

```sh
$ docker run -d -p 3000:3000 --name convert convert
```

##### Turn on debugging
```sh
$ docker run -d -p 3000:3000 -e "APP_ENV=local" -e "APP_DEBUG=true" --name convert convert
```

## Usage

Post the file you want to convert and get the converted file in return.
The url for the convert webservice is http://dockerip/convert/{new-file-format}

##### Creating a pdf from docx
```sh
$ curl --form file=@somedocument.docx http://192.168.99.100:3000/convert/pdf > newfile.pdf
```

##### Creating a jpg from docx
```sh
$ curl --form file=@somedocument.docx http://192.168.99.100:3000/convert/jpg > newfile.jpg
```