## Upload file to Magento 2 using webapi REST
 
##### Upload PDF using curl

```
curl --location --request POST '<host>/rest/V1/webapidocuments/upload' \
--header 'Authorization: Bearer <bearer token>' \
--header 'Content-Type: multipart/form-data; boundary=----something' \
--form 'filename=@<directory>/<filename>.pdf'
```

##### Upload PDF using HTTP request

```
POST /rest/V1/webapidocuments/upload HTTP/1.1
Host: <host>
Authorization: Bearer <bearer token>
Content-Type: multipart/form-data; boundary=----something

----something
Content-Disposition: form-data; name="filename"; filename="<filename>.pdf"
Content-Type: application/pdf

(data)
----something

```
