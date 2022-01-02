package com.tera

import groovy.json.JsonSlurper

import java.lang.System
import groovy.sql.Sql
import grails.converters.*
import groovy.xml.MarkupBuilder
import org.grails.web.json.JSONObject

class AccountController {
	/*static allowedMethods = [
			GetAccountInfoByUserNoJSON:['POST', 'GET'],
			PortalLoginJSON:['POST', 'GET'],
			GameLoginJSON:['POST', 'GET'],
			GetUserInfo:['POST', 'GET']
	]*/

	def dataSource

	def CheckMysqlAlive() {
		String selectQuery = "select count(*) from AccountInfo"
		Sql sql = new Sql(dataSource)
		sql.execute(selectQuery)
		if(sql)
			println '--------------> Keep Mysql Alive Success ' +System.currentTimeMillis()
		else
			println 'xxxxxxxxxxxxxx> Keep Mysql Alive failed ' +System.currentTimeMillis()
		sql.close()
		JSONObject result = new JSONObject()
		result.put('ReturnCode', 0)
		response.setContentLength((result as JSON).toString()?.size())
		render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
	}

	def renderMap = { map ->
		return {
			for ( entry in map ){
				switch( entry.value.getClass() ){
					case Map :
						"${entry.key}" renderMap( entry.value )
						break
					default :
						"${entry.key}"( "${entry.value}" )
						break
				}
			}
		}
	}

	def renderResultToJsonOrXml = { result, xml ->
		if(xml){
			def writer = new StringWriter()
			new MarkupBuilder(writer).root renderMap(result)
			response.setContentLength(writer.toString().bytes.length)
			render text: writer.toString(), contentType: "text/xml", encoding: 'UTF-8'
		}else{
			def resultJson = result as JSON
			if(result?.ReturnCode?.toInteger() != 50050)	//?? ??? ?? ?? ??
				response.setContentLength(resultJson.toString().bytes.length)//( result as JSON).toString()?.size())
			render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
		}
	}

	// Step 1 From Web Call
	def PortalLoginJSON() {
		println '----------------PortalLoginJSON----------------'
		String msg = 'success'
		int returnCode = 0

		def paramMap = [:]
		try {
			params.each { p ->
				if (p.key != 'action' && p.key != 'controller') {
					paramMap.put(p.key, p.value)
					println 'Key: ' + p.key + ', value: ' + p.value
				}
			}
		} catch (RuntimeException e) {
			returnCode = 58007
			msg = 'invalid encoded parameter(base64)'
			e.printStackTrace()
		}

		JSONObject result = new JSONObject()
		if (!params.userID || !params.password) {
			returnCode = 2
			msg = "userID=$params.userID&password=$params.password"
		}
		else
		{
			//remarked by Michael 2020-04-26 Need to check passwd here
			def accountList = []
			String selectQuery = "select * from AccountInfo where userName = '$params.userID'"
			Sql sql = new Sql(dataSource)
			sql.eachRow(
					selectQuery, {
				it.eachWithIndex { row, index ->
					accountList << [
							user_srl:	row.accountDBID,
							passWord:	row.passWord,
							charCount:	row.charCount,
							isBlocked:	row.isBlocked,
					]
				}
			}
			)
			sql.close()
			if (accountList.size() != 1) {
				msg = 'account not exist'
				returnCode = 50000
			}
			else {
				def secret_salt = 'TERAISNOTTHATGOODLMAO'
				def pass_plain = params.password
				def pwd_salt = secret_salt + pass_plain
				def pass_sha512 = pwd_salt.digest('SHA-512')
				
				def accountInfo = accountList.get(0)
				if (pass_sha512 != accountInfo.getAt('passWord')) {
					msg = 'password error'
					returnCode = 50015
				}
				else {
					def newAuthKey = UUID.randomUUID().toString()
					String insertQuery = "update AccountInfo set authKey = '$newAuthKey' where userName = '$params.userID'"
					Sql sqlinsert = new Sql(dataSource)
					try {
						sqlinsert.execute(insertQuery)
					} catch (Exception e) {
						msg = 'failure insert auth token'
						returnCode = 50811
						e.printStackTrace()
					} finally {
						sql.close()
					}
					if (returnCode == 0) {
						def CharacterCount
						switch (accountInfo.getAt('charCount')) {
							case 1:
								CharacterCount = '0|2800,1|'
								break
							case 2:
								CharacterCount = '0|2800,2|'
								break
							case 3:
								CharacterCount = '0|2800,3|'
								break
							default:
								CharacterCount = '0|2800,0|'
								break
						}
						result.put('FailureCount', 0)
						result.put('Permission', accountInfo.getAt('isBlocked').toString())
						result.put('AuthKey', newAuthKey)
						result.put('UserNo', accountInfo.getAt('user_srl'))
						result.put('VipitemInfo', false)
						result.put('CharacterCount', CharacterCount)
						result.put('PassitemInfo', false)
						result.put('phoneLock', false)

						JSONObject UserStatus = new JSONObject()
						UserStatus.put('enumType', 'com.common.auth.User$UserStatus')
						UserStatus.put('name', 'JOIN')
						result.put('UserStatus', UserStatus)
					}
				}
			}
		}
		result.put('ReturnCode', returnCode)
		result.put('Return', returnCode > 1 ? false : true)
		result.put('msg', msg)
		response.setContentLength((result as JSON).toString()?.size())
		render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
		println result
	}

	//Step 2 From Web Call
	def GetAccountInfoByUserNoJSON(){
		println '----------------GetAccountInfoByUserNoJSON----------------'
		String msg = 'success'
		int returnCode = 0

		def paramMap = [:]
		try {
			params.each {p->
				if (p.key != 'action' && p.key != 'controller') {
					paramMap.put(p.key, p.value)
					println 'Key: '+p.key+', value: '+p.value
				}
			}
		} catch (RuntimeException e) {
			returnCode 	= 58007
			msg 		= 'invalid encoded parameter(base64)'
			e.printStackTrace()
		}

		//remarked by Michael 2020-04-26 Need to check passwd here
		JSONObject result = new JSONObject()
		if (!params.id) {
			returnCode = 2
			msg = "id=$params.id"
		}
		else
		{
			def accountList = []
			String selectQuery = "select * from AccountInfo where accountDBID = $params.id"
			Sql sql = new Sql(dataSource)
			sql.eachRow(
					selectQuery, {
				it.eachWithIndex { row, index ->
					accountList << [
							charCount:	row.charCount,
							isBlocked:	row.isBlocked,
					]
				}
			}
			)
			sql.close()
			if (accountList.size() != 1) {
				msg = 'invalid login request'
				returnCode = 50000
			}
			else {
				def accountInfo = accountList.get(0)
				def CharacterCount
				switch(accountInfo.getAt('charCount'))
				{
					case 1:
						CharacterCount = '0|2800,1|'
						break
					case 2:
						CharacterCount = '0|2800,2|'
						break
					case 3:
						CharacterCount = '0|2800,3|'
						break
					default:
						CharacterCount = '0|2800,0|'
						break
				}
				result.put('permission', accountInfo.getAt('isBlocked').toString())
				result.put('charcountstr', CharacterCount)
				result.put('passitemInfo', false)
				result.put('vipitemInfo', false)
			}
		}
		if (returnCode > 0)
			result.put("msg", msg)
		response.setContentLength(( result as JSON).toString()?.size())
		render text : result as JSON, contentType: 'application/json', encoding: 'UTF-8'
		println result
	}

	//Step 3
	/**
	 * Web Authentication(AuthKey) Login API
	 * @param userNo, serviceCode, authKey, clientIP
	 * @return AuthKey, ReturnCode, etc
	 */
	def GameLoginJSON(){
		println '----------------GameLoginJSON----------------'
		String msg = ''
		int returnCode = 0
		boolean paramLengthCheck = true

		params.putAll(request.JSON)
		params.each {
			if(it.getValue().toString().contains('./') || it.getValue().toString().contains('.?') || it.getValue().toString().length() > 1500)
				paramLengthCheck = false
			msg = "$it.key parameter error"
		}

		if(!paramLengthCheck){
			JSONObject result = new JSONObject()
			result.put('Return',false)
			result.put('ReturnCode',50500)
			result.put('msg', msg)
			def resultJson = result as JSON
			response.setContentLength(resultJson.toString()?.bytes.length)
			render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
			println result
		}
		else {
			JSONObject result = new JSONObject()
			def paramMap = [:]
			try {
				params.each { p ->
					if (p.key != 'action' && p.key != 'controller') {
						paramMap.put(p.key, p.value)
						println 'Key: ' + p.key + ', value: ' + p.value
					}
				}
			} catch (RuntimeException e) {
				returnCode = 58007
				msg = 'invalid encoded parameter(base64)'
				e.printStackTrace()
			}

			def start = java.lang.System.currentTimeMillis()
			msg = 'success'

			//remarked by Michael 2020-04-29 Need to check AuthKey here
			def accountList = []
			String selectQuery = "select * from AccountInfo where accountDBID = $params.userNo"
			Sql sql = new Sql(dataSource)
			sql.eachRow(
					selectQuery, {
				it.eachWithIndex { row, index ->
					accountList << [
							AuthKey:	row.AuthKey,
					]
				}
			}
			)
			sql.close()
			if (accountList.size() != 1) {
				msg = 'invalid login request'
				returnCode = 50000
			}
			else {
				def accountInfo = accountList.get(0)
				if (accountInfo.getAt('AuthKey') != params.authKey) {
					returnCode = 50011
					msg = 'authkey mismatch'
				} else {
					result.put('UserNo', params.userNo)
					result.put('msg', msg)
					result.put('AuthKey', params.authKey)
					result.put('UserType', 'PURCHASE')
					result.put('UserID', params.userNo)
					result.put('isUsedOtp', false)
				}
			}
			result.put('Return', returnCode > 1 ? false : true)
			result.put('ReturnCode', returnCode)
			if (returnCode > 0)
				result.put("msg", msg)
			params.put('ms', (System.currentTimeMillis() - start))
			renderResultToJsonOrXml(result, params.xml)
			println result
		}
	}

def GetUserInfo() {
        println '----------------GetUserInfo----------------'
        String msg = ''
        int returnCode = 0
        boolean paramLengthCheck = true

        params.putAll(request.JSON)
        params.each {
            if(it.getValue().toString().contains('./') || it.getValue().toString().contains('.?') || it.getValue().toString().length() > 1500)
                paramLengthCheck = false
            msg = "$it.key parameter error"
        }

        if(!paramLengthCheck){
            JSONObject result = new JSONObject()
            result.put('Return',false)
            result.put('ReturnCode',50500)
            result.put('msg', msg)
            def resultJson = result as JSON
            response.setContentLength(resultJson.toString()?.bytes.length)
            render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
            println result
        }
        else {
            JSONObject result = new JSONObject()
            def paramMap = [:]
            try {
                params.each { p ->
                    if (p.key != 'action' && p.key != 'controller') {
                        paramMap.put(p.key, p.value)
                        println 'Key: ' + p.key + ', value: ' + p.value
                    }
                }
            } catch (RuntimeException e) {
                returnCode = 58007
                msg = 'invalid encoded parameter(base64)'
                e.printStackTrace()
            }

            if(!params.user_srl || !params.server_id ||!params.ip || !params.serviceCode ){
                returnCode = 2
                msg = "user_srl=$params.user_srl&server_id=$params.server_id&ip=$params.ip&serviceCode=$params.serviceCode"
            }

            def accountList = []
            String selectQuery = "select * from AccountInfo where accountDBID = $params.user_srl"
            Sql sql = new Sql(dataSource)
            sql.eachRow(
                    selectQuery, {
                it.eachWithIndex { row, index ->
                    accountList << [
                            charCount:        row.charCount,
                            //lastLoginTime:    row.lastLoginTime,
                            //playTimeLast:    row.playTimeLast,
                            isBlocked:        row.isBlocked,
							privilege: row.privilege,
                    ]
                }
            }
            )
            sql.close()

            def benefit_array = []
            selectQuery = "select * from account_benefits where accountDBID = $params.user_srl"
            sql = new Sql(dataSource)
            sql.eachRow(
                    selectQuery, {
                        it.eachWithIndex { row, index ->
                        benefit_array << [
                                row.benefitId,
                                row.availableUntil - (System.currentTimeMillis()/1000).toInteger()
                        ]
                    }
                }
            )
            if (accountList.size() != 1) {
                msg = 'invalid login request'
                returnCode = 50000
            }
            else {
                def accountInfo = accountList.get(0)
                def charCount = accountInfo.getAt('charCount')
                def CharacterCount = "0|2800,$charCount|"
                result.put("last_connected_server", null)
                result.put("last_play_time", null)
                result.put("logout_time_diff", null)
                result.put("char_count_info", CharacterCount)
                result.put("privilege", accountInfo.getAt('privilege'))
                result.put("permission", accountInfo.getAt('isBlocked'))
                result.put("result_code", returnCode)
                result.put("benefit", benefit_array)
                result.put("vip_pub_exp", 0)
            }
            if (returnCode > 0)
                result.put("msg", msg)
            response.setContentLength((result as JSON).toString()?.size())
            render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
            println result
        }
    }


	/**
	 * @params : user_srl, server_id, ip, serviceCode
	 * @return : int result_code, string msg
	 */
	def EnterGame() {
		println '----------------EnterGame----------------'
		String msg = ''
		int result_code = 0
		boolean paramLengthCheck = true

		params.putAll(request.JSON)
		params.each {
			if(it.getValue().toString().contains('./') || it.getValue().toString().contains('.?') || it.getValue().toString().length() > 1500)
				paramLengthCheck = false
			msg = "$it.key parameter error"
		}
		if(!paramLengthCheck){
			JSONObject result = new JSONObject()
			result.put('Return',false)
			result.put('ReturnCode',50500)
			result.put('msg', msg)
			def resultJson = result as JSON
			response.setContentLength(resultJson.toString()?.bytes.length)
			render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
		}
		else {
			def paramMap = [:]
			try {
				params.each { p ->
					if (p.key != 'action' && p.key != 'controller') {
						paramMap.put(p.key, p.value)
						println 'Key: ' + p.key + ', value: ' + p.value
					}
				}
			} catch (RuntimeException e) {
				result_code = 58007
				msg = 'invalid encoded parameter(base64)'
				e.printStackTrace()
			}
			if(!params.user_srl || !params.server_id ||!params.ip || !params.serviceCode ){
				result_code = 2
				msg = "user_srl=$params.user_srl&server_id=$params.server_id&ip=$params.ip&serviceCode=$params.serviceCode"
				//?user_srl=27581&server_id=2727&ip=1.1.1.1&serviceCode=PCO012
			}
			else
			{
			}
			JSONObject result = new JSONObject()
			result.put("result_code",result_code)
			if(result_code > 0)
				result.put("msg",msg)
			response.setContentLength((result as JSON).toString()?.size())
			render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
			println result
		}
	}

	/**
	 * @params : user_srl, play_time, serviceCode
	 * @return : int result_code, string msg
	 */
	def LeaveGame() {
		println '----------------LeaveGame----------------'
		String msg = ''
		int result_code = 0
		boolean paramLengthCheck = true

		params.putAll(request.JSON)
		params.each {
			if(it.getValue().toString().contains('./') || it.getValue().toString().contains('.?') || it.getValue().toString().length() > 1500)
				paramLengthCheck = false
			msg = "$it.key parameter error"
		}
		if(!paramLengthCheck){
			JSONObject result = new JSONObject()
			result.put('Return',false)
			result.put('ReturnCode',50500)
			result.put('msg', msg)
			def resultJson = result as JSON
			response.setContentLength(resultJson.toString()?.bytes.length)
			render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
		}
		else {
			def paramMap = [:]
			try {
				params.each { p ->
					if (p.key != 'action' && p.key != 'controller') {
						paramMap.put(p.key, p.value)
						println 'Key: ' + p.key + ', value: ' + p.value
					}
				}
			} catch (RuntimeException e) {
				result_code = 58007
				msg = 'invalid encoded parameter(base64)'
				e.printStackTrace()
			}
			if (!params.user_srl || !params.serviceCode) {
				result_code = 2
				msg = "user_srl=$params.user_srl&play_time=$params.play_time&serviceCode=$params.serviceCode"
				//?user_srl=27581&server_id=2727&ip=1.1.1.1&serviceCode=PCO012
			} else {
			}

			JSONObject result = new JSONObject()
			result.put("result_code", result_code)
			if (result_code > 0)
				result.put("msg", msg)
			response.setContentLength((result as JSON).toString()?.size())
			render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
			println result
		}
	}
	/**
	 * @params :
	 int64 user_srl
	 int server_id
	 int char_srl
	 string char_name
	 int race_id
	 int class_id
	 int gender_id
	 int level
	 serviceCode
	 * @return : int result_code, string msg
	 */
	def CreateChar() {
		println '----------------CreateChar----------------'
		String msg = ''
		int result_code = 0
		boolean paramLengthCheck = true

		params.putAll(request.JSON)
		params.each {
			if(it.getValue().toString().contains('./') || it.getValue().toString().contains('.?') || it.getValue().toString().length() > 1500)
				paramLengthCheck = false
			msg = "$it.key parameter error"
		}
		if(!paramLengthCheck){
			JSONObject result = new JSONObject()
			result.put('Return',false)
			result.put('ReturnCode',50500)
			result.put('msg', msg)
			def resultJson = result as JSON
			response.setContentLength(resultJson.toString()?.bytes.length)
			render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
		}
		else {
			def paramMap = [:]
			try {
				params.each { p ->
					if (p.key != 'action' && p.key != 'controller') {
						paramMap.put(p.key, p.value)
						println 'Key: ' + p.key + ', value: ' + p.value
					}
				}
			} catch (RuntimeException e) {
				result_code = 58007
				msg = 'invalid encoded parameter(base64)'
				e.printStackTrace()
			}

			JSONObject result = new JSONObject()
			result.put("result_code", result_code)
			if (result_code > 0)
				result.put("msg", msg)
			response.setContentLength((result as JSON).toString()?.size())
			render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
			println result
		}
	}

	/**
	 * @params : user_srl, server_id, char_srl, serviceCode
	 * @return : result_code, result_msg
	 */
	def DeleteChar() {
		println '----------------DeleteChar----------------'
		String msg = ''
		int result_code = 0
		boolean paramLengthCheck = true

		params.putAll(request.JSON)
		params.each {
			if(it.getValue().toString().contains('./') || it.getValue().toString().contains('.?') || it.getValue().toString().length() > 1500)
				paramLengthCheck = false
			msg = "$it.key parameter error"
		}
		if(!paramLengthCheck){
			JSONObject result = new JSONObject()
			result.put('Return',false)
			result.put('ReturnCode',50500)
			result.put('msg', msg)
			def resultJson = result as JSON
			response.setContentLength(resultJson.toString()?.bytes.length)
			render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
		}
		else {
			def paramMap = [:]
			try {
				params.each { p ->
					if (p.key != 'action' && p.key != 'controller') {
						paramMap.put(p.key, p.value)
						println 'Key: ' + p.key + ', value: ' + p.value
					}
				}
			} catch (RuntimeException e) {
				result_code = 58007
				msg = 'invalid encoded parameter(base64)'
				e.printStackTrace()
			}

			JSONObject result = new JSONObject()
			result.put("result_code", result_code)
			if (result_code > 0)
				result.put("msg", msg)
			response.setContentLength((result as JSON).toString()?.size())
			render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
			println result
		}
	}

	/**
	 * @params : userNO, charID, itemID, itemCount, passWord
	 * @return : result_code, result_msg
	 */
	/*
	def tempTeraItemSend() {
		println '----------------tempTeraItemSend----------------'
		String msg = 'success'
		int result_code = 0
		def userNO = params.userNO
		def charID = params.charID
		int worldNo = 2800
		JSONObject result = new JSONObject()
		//if (params.passWord != 'fuckHack110AndPig') {
		//	msg = 'invalid request'
		//	result_code = 1
		//} else {
			def sendItemList = []
			sendItemList << [
					item_id   : params.itemID,
					item_count: params.itemCount
			]
			sendItemByCharacter(6, sendItemList, worldNo, "Send gift for you...", null, "GiftBox01.bmp", 1, 10)
			println sendItemList
		//}
		result.put("result_code", result_code)
		result.put("msg", msg)
		response.setContentLength((result as JSON).toString()?.size())
		render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
	}
	*/

	/**
	 * @param user
	 * @param sendItemInfoList
	 * 			[{
	 *				'item_id' : Integer
	 *				'item_count : Integer
	 *			},{
	 *				..
	 *				..
	 *			}]
	 * @param worldNo
	 * @param title
	 * @param content
	 * @param icon
	 * @param logId
	 * @return
	 */
	/*
	def sendItemByCharacter(int userNo, def sendItemInfoList, int worldNo, String title, String content, String icon, int logId, int charId)
	{
		println '----------------sendItemByCharacter----------------'
		HttpHelper httpHelper = new HttpHelper()
		int returnCode = 1
		def fcgiParam = [:]

		fcgiParam.user_srl = userNo
		fcgiParam.char_srl = charId
		fcgiParam.title = title
		fcgiParam.content = content
		fcgiParam.icon = icon
		fcgiParam.log_id = logId
		fcgiParam.items = sendItemInfoList
		fcgiParam.svr_id = worldNo

		println '----------------0. sendItemByCharacter----------------'
		def fcgiMakeBoxResponse = httpHelper.post('http://127.0.0.1:10002/fcgi', '/make_box', fcgiParam, false)
		String makeBoxApiCallLog = "[param : " + (fcgiParam as JSON).toString() + "] / [response : $fcgiMakeBoxResponse]"
		println makeBoxApiCallLog
		if (returnCode == 1) {
			if (fcgiMakeBoxResponse.equals('57000')) {
				println '----------------1. sendItemByCharacter----------------'
				returnCode = 57000
			}
		}
		if (returnCode == 1) {
			def fcgiMakeBoxResponseArray = fcgiMakeBoxResponse.split(' ')
			if (fcgiMakeBoxResponseArray[0].toString().equals('0') || fcgiMakeBoxResponseArray[0].toString().isNumber() == false) {
				println '----------------2. sendItemByCharacter----------------'
				String errorMessage = "[param : " + (fcgiParam as JSON).toString() + "] / [response : $fcgiMakeBoxResponse]"
				println errorMessage
				returnCode = 50002
			}
		}
		println '----------------3. sendItemByCharacter----------------'
		if (returnCode == 1) {
			def tempReturnCode = makeBoxNoti(userNo)
			println tempReturnCode
		}
		return returnCode
	}
	*/

	/**
	 * @param user
	 * @param worldNo
	 * @return
	 */
	/*
	def makeBoxNoti(int userNo)
	{
		println '----------------makeBoxNoti----------------'
		Integer returnCode = 1

		HttpHelper httpHelper = new HttpHelper()
		def queryUrl = String.format("%s/%s", '/query.json', userNo)
		def queryData = httpHelper.get('http://127.0.0.1:10002/fcgi', queryUrl, null)

		try {
			if (queryData.user_loc.toString().toInteger() != 0) {
				def fcgiMakeBoxNotiResponse
				if (returnCode == 1) {
					//GET /box_noti/:server_id/:game_account_idl/:char_srl
					String RESTGetUrl = String.format(
							"%s/%s/%s/0",
							'/box_noti',
							queryData.user_loc,
							userNo
					)
					fcgiMakeBoxNotiResponse = httpHelper.get('http://10.45.126.103:10002/fcgi', RESTGetUrl, null, false)
					if (fcgiMakeBoxNotiResponse.equals('57000')) {
						returnCode = 57000
					}
					String notiLog = "[response : $fcgiMakeBoxNotiResponse] / [Url : $RESTGetUrl]"
					println notiLog
					println 'makeBoxNoti returnCode: '+returnCode
				}
			}
		} catch (Exception ex) {
			ex.printStackTrace()
			returnCode = 57000
		}
		return returnCode
	}
	*/

	/**
	 * @params : user_srl, server_id, char_srl, serviceCode
	 * @return
	 */
	def ModifyChar() {
		println '----------------ModifyChar----------------'
		String msg = ''
		int result_code = 0
		boolean paramLengthCheck = true

		params.putAll(request.JSON)
		params.each {
			if(it.getValue().toString().contains('./') || it.getValue().toString().contains('.?') || it.getValue().toString().length() > 1500)
				paramLengthCheck = false
			msg = "$it.key parameter error"
		}
		if(!paramLengthCheck){
			JSONObject result = new JSONObject()
			result.put('Return',false)
			result.put('ReturnCode',50500)
			result.put('msg', msg)
			def resultJson = result as JSON
			response.setContentLength(resultJson.toString()?.bytes.length)
			render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
		}
		else {
			def paramMap = [:]
			try {
				params.each { p ->
					if (p.key != 'action' && p.key != 'controller') {
						paramMap.put(p.key, p.value)
						println 'Key: ' + p.key + ', value: ' + p.value
					}
				}
			} catch (RuntimeException e) {
				result_code = 58007
				msg = 'invalid encoded parameter(base64)'
				e.printStackTrace()
			}

			JSONObject result = new JSONObject()
			result.put("result_code", result_code)
			if (result_code > 0)
				result.put("msg", msg)
			response.setContentLength((result as JSON).toString()?.size())
			render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
			println result
		}
	}

	/**
	 * @params user_srl, server_id, chrono_id (222687~222692)
	 * @return int result_code, string msg
	 */
	//{"server_id":3000,"chrono_id":222688,"serviceCode":"PCO012","user_srl":990001559}
	def UseChronoScroll() {
		println '----------------UseChronoScroll----------------'
		String msg = ''
		int result_code = 0
		boolean paramLengthCheck = true

		params.putAll(request.JSON)
		params.each {
			if(it.getValue().toString().contains('./') || it.getValue().toString().contains('.?') || it.getValue().toString().length() > 1500)
				paramLengthCheck = false
			msg = "$it.key parameter error"
		}
		if(!paramLengthCheck){
			JSONObject result = new JSONObject()
			result.put('Return',false)
			result.put('ReturnCode',50500)
			result.put('msg', msg)
			def resultJson = result as JSON
			response.setContentLength(resultJson.toString()?.bytes.length)
			render text : resultJson , contentType: 'application/json', encoding: 'UTF-8'
		}
		else {
			def paramMap = [:]
			try {
				params.each { p ->
					if (p.key != 'action' && p.key != 'controller') {
						paramMap.put(p.key, p.value)
						println 'Key: ' + p.key + ', value: ' + p.value
					}
				}
			} catch (RuntimeException e) {
				result_code = 58007
				msg = 'invalid encoded parameter(base64)'
				e.printStackTrace()
			}
			/*
			def fcgiAddBenefitResponse
			String RESTGetUrl = String.format("%s/%s/%s/%s/%s","/add_benefit", 2800, 6, 1, 86400)
			fcgiAddBenefitResponse = HttpHelper.get("http://127.0.0.1:10002/fcgi", RESTGetUrl, null, false)

			if (!fcgiAddBenefitResponse.equals('0')) {
				result_code = Integer.parseInt(fcgiAddBenefitResponse.toString()) // ??
			}

			String notiLog = "[response : $fcgiAddBenefitResponse] / [Url : $RESTGetUrl]"
			println notiLog
			//Logger.log3(HostHelper.getTeraFCGIHost(worldNo) + RESTGetUrl, "teraBenefitLog/teraAddBenefitLog")
			//Logger.log3(notiLog, "teraBenefitLog/teraAddBenefitLog")
			*/
			JSONObject result = new JSONObject()
			result.put("result_code", result_code)
			if (result_code > 0)
				result.put("msg", msg)
			response.setContentLength((result as JSON).toString()?.size())
			render text: result as JSON, contentType: 'application/json', encoding: 'UTF-8'
			println result
		}
	}

}
