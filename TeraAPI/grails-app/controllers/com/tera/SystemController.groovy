package com.tera

import java.lang.System
import grails.converters.*
import org.grails.web.json.JSONObject

class SystemController {
	/*static allowedMethods = [
			RequestAPIServerStatusAvailable:['POST', 'GET'],
			ServiceTest:['POST', 'GET'],
			GetServerPermission:['POST', 'GET'],
			ServerDown:['POST', 'GET']
	]*/

	def RequestAPIServerStatusAvailable() {
		println 'RequestAPIServerStatusAvailable'

		JSONObject result = new JSONObject()
		result.put('Return',true)

		response.setContentLength(( result as JSON).toString()?.size())
		render text : result as JSON, contentType: 'application/json', encoding: 'UTF-8'
	}

	def ServiceTest() {
		println 'ServiceTest'

		int result_code = 0
		JSONObject result = new JSONObject()
		result.put("server_time",getNowSecond())
		result.put("result_code",result_code)

		response.setContentLength(( result as JSON).toString()?.size())
		render text : result as JSON, contentType: "application/json", encoding: "UTF-8"
	}

	def GetServerPermission() {
		println 'GetServerPermission'

		int result_code = 0
		int permission = 0
		JSONObject result = new JSONObject()
		result.put("permission", permission)
		result.put("result_code",result_code)

		response.setContentLength(( result as JSON).toString()?.size())
		render text : result as JSON, contentType: "application/json", encoding: "UTF-8"
	}

	def ServerDown() {
		println 'ServerDown'

		int result_code = 0
		JSONObject result = new JSONObject()
		result.put("result_code",result_code)

		response.setContentLength(( result as JSON).toString()?.size())
		render text : result as JSON, contentType: "application/json", encoding: "UTF-8"
	}

	def getNowSecond() {
		return (System.currentTimeMillis()/1000).toInteger()
	}

}
