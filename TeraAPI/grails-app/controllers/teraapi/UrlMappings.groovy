package teraapi

class UrlMappings {

    static mappings = {
        "/$controller/$action?/$id?(.$format)?"{
            constraints {
                // apply constraints here
            }
        }

        "/"(view:"/index")
        "500"(view:'/error')
        "404"(view:'/notFound')

        //remrked by Michel 2020-04-26 Used for ArbiterGW
        get "/systemApi/RequestAPIServerStatusAvailable"(controller: 'System', action:'RequestAPIServerStatusAvailable')
        get "/api/ServiceTest"(controller: 'System', action:'ServiceTest')
        post "/api/GetServerPermission"(controller: 'System', action:'GetServerPermission')
        post "/api/ServerDown"(controller: 'System', action:'ServerDown')

        //remrked by Michel 2020-04-26 Used for Client
        //post "/tera/TeraItemSend"(controller: 'Account', action:'tempTeraItemSend')
        post "/tera/GetAccountInfoByUserNo"(controller: 'Account', action:'GetAccountInfoByUserNoJSON')
        post "/tera/LauncherLoginAction"(controller: 'Account', action:'PortalLoginJSON')
        post "/authApi/GameAuthenticationLogin"(controller: 'Account', action:'GameLoginJSON')
        post "/api/GetUserInfo"(controller: 'Account', action:'GetUserInfo')
        post "/api/EnterGame"(controller: 'Account', action:'EnterGame')
        post "/api/LeaveGame"(controller: 'Account', action:'LeaveGame')
        post "/api/CreateChar"(controller: 'Account', action:'CreateChar')
        post "/api/DeleteChar"(controller: 'Account', action:'DeleteChar')
        post "/api/ModifyChar"(controller: 'Account', action:'ModifyChar')
        post "/api/UseChronoScroll"(controller: 'Account', action:'UseChronoScroll')
    }
}
