@if "%DEBUG%" == "" @echo off
@rem ##########################################################################
@rem
@rem  grails startup script for Windows
@rem
@rem ##########################################################################

@rem Set local scope for the variables with windows NT shell
if "%OS%"=="Windows_NT" setlocal

set DIRNAME=%~dp0
if "%DIRNAME%" == "" set DIRNAME=.
set APP_BASE_NAME=%~n0
set APP_HOME=%DIRNAME%..

@rem Add default JVM options here. You can also use JAVA_OPTS and GRAILS_OPTS to pass JVM options to this script.
set DEFAULT_JVM_OPTS="-XX:+TieredCompilation" "-XX:TieredStopAtLevel=1" "-XX:CICompilerCount=3"

@rem Find java.exe
if defined JAVA_HOME goto findJavaFromJavaHome

set JAVA_EXE=java.exe
%JAVA_EXE% -version >NUL 2>&1
if "%ERRORLEVEL%" == "0" goto init

echo.
echo ERROR: JAVA_HOME is not set and no 'java' command could be found in your PATH.
echo.
echo Please set the JAVA_HOME variable in your environment to match the
echo location of your Java installation.

goto fail

:findJavaFromJavaHome
set JAVA_HOME=%JAVA_HOME:"=%
set JAVA_EXE=%JAVA_HOME%/bin/java.exe

if exist "%JAVA_EXE%" goto init

echo.
echo ERROR: JAVA_HOME is set to an invalid directory: %JAVA_HOME%
echo.
echo Please set the JAVA_HOME variable in your environment to match the
echo location of your Java installation.

goto fail

:init
@rem Get command-line arguments, handling Windows variants

if not "%OS%" == "Windows_NT" goto win9xME_args

:win9xME_args
@rem Slurp the command line arguments.
set CMD_LINE_ARGS=
set _SKIP=2

:win9xME_args_slurp
if "x%~1" == "x" goto execute

set CMD_LINE_ARGS=%*

:execute
@rem Setup the command line

set CLASSPATH=%APP_HOME%\dist\grails-shell-4.0.3.jar;%APP_HOME%\dist\grails-bootstrap-4.0.3.jar;%APP_HOME%\dist\grails-gradle-model-4.0.3.jar;%APP_HOME%\lib\org.codehaus.groovy\groovy-ant\jars\groovy-ant-2.5.6.jar;%APP_HOME%\lib\org.codehaus.groovy\groovy-json\jars\groovy-json-2.5.6.jar;%APP_HOME%\lib\org.codehaus.groovy\groovy-jmx\jars\groovy-jmx-2.5.6.jar;%APP_HOME%\lib\org.codehaus.groovy\groovy-templates\jars\groovy-templates-2.5.6.jar;%APP_HOME%\lib\org.codehaus.groovy\groovy-xml\jars\groovy-xml-2.5.6.jar;%APP_HOME%\lib\org.codehaus.groovy\groovy\jars\groovy-2.5.6.jar;%APP_HOME%\lib\org.slf4j\jcl-over-slf4j\jars\jcl-over-slf4j-1.7.30.jar;%APP_HOME%\lib\org.gradle\gradle-tooling-api\jars\gradle-tooling-api-5.0.jar;%APP_HOME%\lib\org.slf4j\slf4j-simple\jars\slf4j-simple-1.7.30.jar;%APP_HOME%\lib\org.slf4j\slf4j-api\jars\slf4j-api-1.7.30.jar;%APP_HOME%\lib\org.apache.ant\ant-junit\jars\ant-junit-1.9.13.jar;%APP_HOME%\lib\org.apache.ant\ant\jars\ant-1.9.13.jar;%APP_HOME%\lib\org.fusesource.jansi\jansi\jars\jansi-1.18.jar;%APP_HOME%\lib\org.springframework.boot\spring-boot-cli\jars\spring-boot-cli-2.1.13.RELEASE.jar;%APP_HOME%\lib\jline\jline\jars\jline-2.14.6.jar;%APP_HOME%\lib\org.codehaus.plexus\plexus-component-api\jars\plexus-component-api-1.0-alpha-33.jar;%APP_HOME%\lib\org.yaml\snakeyaml\jars\snakeyaml-1.23.jar;%APP_HOME%\lib\org.apache.ant\ant-launcher\jars\ant-launcher-1.9.13.jar;%APP_HOME%\lib\org.codehaus.groovy\groovy-groovydoc\jars\groovy-groovydoc-2.5.6.jar;%APP_HOME%\lib\org.apache.ant\ant-antlr\jars\ant-antlr-1.9.13.jar;%APP_HOME%\lib\org.springframework.boot\spring-boot-loader-tools\jars\spring-boot-loader-tools-2.1.13.RELEASE.jar;%APP_HOME%\lib\com.vaadin.external.google\android-json\jars\android-json-0.0.20131108.vaadin1.jar;%APP_HOME%\lib\net.sf.jopt-simple\jopt-simple\jars\jopt-simple-5.0.4.jar;%APP_HOME%\lib\org.apache.maven\maven-settings-builder\jars\maven-settings-builder-3.5.4.jar;%APP_HOME%\lib\org.sonatype.plexus\plexus-sec-dispatcher\jars\plexus-sec-dispatcher-1.4.jar;%APP_HOME%\lib\org.sonatype.sisu\sisu-inject-plexus\jars\sisu-inject-plexus-2.6.0.jar;%APP_HOME%\lib\org.springframework\spring-core\jars\spring-core-5.1.14.RELEASE.jar;%APP_HOME%\lib\org.springframework.security\spring-security-crypto\jars\spring-security-crypto-5.1.8.RELEASE.jar;%APP_HOME%\lib\org.apache.maven.resolver\maven-resolver-transport-http\jars\maven-resolver-transport-http-1.1.1.jar;%APP_HOME%\lib\org.apache.httpcomponents\httpclient\jars\httpclient-4.5.11.jar;%APP_HOME%\lib\org.apache.maven\maven-resolver-provider\jars\maven-resolver-provider-3.5.4.jar;%APP_HOME%\lib\org.apache.maven\maven-model-builder\jars\maven-model-builder-3.5.4.jar;%APP_HOME%\lib\org.apache.maven\maven-model\jars\maven-model-3.5.4.jar;%APP_HOME%\lib\org.apache.maven.resolver\maven-resolver-connector-basic\jars\maven-resolver-connector-basic-1.1.1.jar;%APP_HOME%\lib\org.apache.maven.resolver\maven-resolver-impl\jars\maven-resolver-impl-1.1.1.jar;%APP_HOME%\lib\org.apache.maven.resolver\maven-resolver-transport-file\jars\maven-resolver-transport-file-1.1.1.jar;%APP_HOME%\lib\org.eclipse.sisu\org.eclipse.sisu.plexus\jars\org.eclipse.sisu.plexus-0.3.0.jar;%APP_HOME%\lib\org.codehaus.plexus\plexus-classworlds\jars\plexus-classworlds-2.5.2.jar;%APP_HOME%\lib\junit\junit\jars\junit-3.8.1.jar;%APP_HOME%\lib\org.apache.commons\commons-compress\jars\commons-compress-1.18.jar;%APP_HOME%\lib\org.apache.maven\maven-settings\jars\maven-settings-3.5.4.jar;%APP_HOME%\lib\org.apache.maven\maven-repository-metadata\jars\maven-repository-metadata-3.5.4.jar;%APP_HOME%\lib\org.apache.maven\maven-artifact\jars\maven-artifact-3.5.4.jar;%APP_HOME%\lib\org.codehaus.plexus\plexus-utils\jars\plexus-utils-3.1.0.jar;%APP_HOME%\lib\org.sonatype.plexus\plexus-cipher\jars\plexus-cipher-1.4.jar;%APP_HOME%\lib\org.codehaus.plexus\plexus-component-annotations\jars\plexus-component-annotations-1.7.1.jar;%APP_HOME%\lib\org.springframework\spring-jcl\jars\spring-jcl-5.1.14.RELEASE.jar;%APP_HOME%\lib\org.apache.httpcomponents\httpcore\jars\httpcore-4.4.13.jar;%APP_HOME%\lib\commons-codec\commons-codec\jars\commons-codec-1.11.jar;%APP_HOME%\lib\org.apache.maven\maven-builder-support\jars\maven-builder-support-3.5.4.jar;%APP_HOME%\lib\org.apache.commons\commons-lang3\jars\commons-lang3-3.5.jar;%APP_HOME%\lib\org.codehaus.plexus\plexus-interpolation\jars\plexus-interpolation-1.24.jar;%APP_HOME%\lib\org.apache.maven.resolver\maven-resolver-spi\jars\maven-resolver-spi-1.1.1.jar;%APP_HOME%\lib\org.apache.maven.resolver\maven-resolver-util\jars\maven-resolver-util-1.1.1.jar;%APP_HOME%\lib\org.apache.maven.resolver\maven-resolver-api\jars\maven-resolver-api-1.1.1.jar;%APP_HOME%\lib\javax.enterprise\cdi-api\jars\cdi-api-1.0.jar;%APP_HOME%\lib\javax.inject\javax.inject\jars\javax.inject-1.jar;%APP_HOME%\lib\org.eclipse.sisu\org.eclipse.sisu.inject\jars\org.eclipse.sisu.inject-0.3.0.jar;%APP_HOME%\lib\javax.annotation\jsr250-api\jars\jsr250-api-1.0.jar

@rem Execute grails
"%JAVA_EXE%" %DEFAULT_JVM_OPTS% %JAVA_OPTS% %GRAILS_OPTS%  -classpath "%CLASSPATH%" org.grails.cli.GrailsCli %CMD_LINE_ARGS%

:end
@rem End local scope for the variables with windows NT shell
if "%ERRORLEVEL%"=="0" goto mainEnd

:fail
rem Set variable GRAILS_EXIT_CONSOLE if you need the _script_ return code instead of
rem the _cmd.exe /c_ return code!
if  not "" == "%GRAILS_EXIT_CONSOLE%" exit 1
exit /b 1

:mainEnd
if "%OS%"=="Windows_NT" endlocal

:omega
