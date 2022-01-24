package com.tera

import groovy.json.JsonBuilder
import groovy.json.JsonSlurper

class HttpHelper {
    def static post (String host, String path, param, Boolean isJsonStringToMapConvertReturn) {
        StringBuffer sb = null
        InputStreamReader isr = null
        OutputStream outputStream = null
        String jsonString = null
        try {
            URL url = null
            url = new URL(host + path)

            HttpURLConnection urlConn = null
            urlConn = (HttpURLConnection) url.openConnection()
            urlConn.setDoOutput(true)
            urlConn.setRequestMethod("POST")
            urlConn.setRequestProperty("Content-Type", "application/json")

            if (isJsonStringToMapConvertReturn) {
                urlConn.setRequestProperty("Accept", "application/json")
            }

            outputStream = urlConn.getOutputStream()

            jsonString = new JsonBuilder(param).toPrettyString()

            outputStream.write(jsonString.getBytes("UTF-8"))
            outputStream.flush()

            if(urlConn.getResponseCode() == 200) {
                isr = new InputStreamReader(urlConn.getInputStream(), "UTF-8")
            } else {
                isr = new InputStreamReader(urlConn.getErrorStream(), "UTF-8")
            }

            sb = new StringBuffer()
            char[] buf = new char[1]
            int len = -1

            while((len = isr.read(buf, 0, buf.length)) != -1) {
                sb.append(new String(buf, 0, len))
            }

            if (outputStream != null) outputStream.close()
            if (isr != null) isr.close()

            if (isJsonStringToMapConvertReturn) {
                def jsonSlurper = new JsonSlurper()
                Map object = jsonSlurper.parseText(sb.toString())

                return object
            } else {
                return sb.toString()
            }
        } catch(Exception ex) {
            String error = "URL : " + host + path + " / Exception : "+ ex.toString()
            if (param != null) {
                error = error + " / jsonString : " + jsonString
            }
            println 'httpPostFail'
            //Logger.log3(error, 'httpPostFail')

            if (outputStream != null) outputStream.close()
            if (isr != null) isr.close()

            if (isJsonStringToMapConvertReturn) {
                return ['returnCode':57000]
            } else {
                return "57000"
            }
        }
    }

    def static get (String host, String path, Map param, Boolean isJsonStringToMapConvertReturn) {
        String requestUrl = host + path
        if (param != null) {
            requestUrl = requestUrl + "?" + param.collect {it}.join('&')
        }

        BufferedReader reader = null
        InputStreamReader isr = null

        try {
            URL obj = new URL(requestUrl)
            HttpURLConnection con = (HttpURLConnection) obj.openConnection()
            con.setRequestMethod("GET")

            if (isJsonStringToMapConvertReturn) {
                con.setRequestProperty("Accept", "application/json")
            }

            int responseCode = con.getResponseCode()

            if(responseCode == 200) {
                isr = new InputStreamReader(con.getInputStream(), "UTF-8")
            } else {
                isr = new InputStreamReader(con.getErrorStream(), "UTF-8")
            }

            reader = new BufferedReader(isr)
            String inputLine
            StringBuffer sb = new StringBuffer()

            while ((inputLine = reader.readLine()) != null) {
                sb.append(inputLine)
            }

            if (isr != null) isr.close()
            if (reader != null) reader.close()



            if (isJsonStringToMapConvertReturn) {
                def jsonSlurper = new JsonSlurper()
                Object object = jsonSlurper.parseText(sb.toString())

                return object
            } else {
                return sb.toString()
            }
        } catch(Exception ex) {
            String error = "URL : " + requestUrl + " / Exception : "+ ex.toString()
            //Logger.log3(error, 'httpGetFail')

            if (isr != null) isr.close()
            if (reader != null) reader.close()

            if (isJsonStringToMapConvertReturn) {
                return ['returnCode':57000]
            } else {
                return "57000"
            }
        }
    }
}
