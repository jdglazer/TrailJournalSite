# pre-requisite is to have the database deployed locally and mysql running

TOP_LEVEL=$(dirname $0)/../..

PROPEL_EXEC=${TOP_LEVEL}/vendor/bin/propel
BUILD_CONFIG_DIR=${TOP_LEVEL}/build/config
BUILD_TARGET_DIR=${TOP_LEVEL}/target

# clean target directory if it exists
rm -rf ${BUILD_TARGET_DIR} 2> /dev/null

# create target directory
mkdir ${TOP_LEVEL}/target

# include mysql properties for connecting to generate orm
. ${TOP_LEVEL}/build/config/dataaccess-build.properties

sed 's/__MYSQL_DB__/'${MYSQL_DATABASE}'/' ${BUILD_CONFIG_DIR}/propel.xml.stub | \
sed 's/__MYSQL_USER__/'${MYSQL_ROOT_USER}'/' | \
sed 's/__MYSQL_USER_PW__/'${MYSQL_ROOT_PW}'/' > ${BUILD_TARGET_DIR}/propel.xml.tmp

mv ${BUILD_TARGET_DIR}/propel.xml.tmp ${BUILD_TARGET_DIR}/propel.xml

# generate propel orm schema.xml
${PROPEL_EXEC} reverse --output-dir ${BUILD_TARGET_DIR} --config-dir ${BUILD_TARGET_DIR} --namespace "DataAccess\\DataAccessObjects"

${TOP_LEVEL}/build/bin/setSchemaXmlEmptyNamespace.py ${BUILD_TARGET_DIR}/schema.xml

# generate orm classes
mkdir ${BUILD_TARGET_DIR}/lib
${PROPEL_EXEC} build --schema-dir ${BUILD_TARGET_DIR} --config-dir ${BUILD_TARGET_DIR} --output-dir ${BUILD_TARGET_DIR}/lib

# generate config.php for initializing connection during runtime
${PROPEL_EXEC} config:convert --config-dir ${BUILD_TARGET_DIR} --output-dir ${BUILD_TARGET_DIR}

# copy into lib dir extended data access object classes
${TOP_LEVEL}/build/bin/copySrcToLib.sh