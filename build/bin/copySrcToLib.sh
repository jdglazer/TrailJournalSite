TOP_LEVEL=$(dirname $0)/../..

BUILD_TARGET_DIR=${TOP_LEVEL}/target

# copy into lib dir extended data access object classes
SRC_LIB_DIR=${TOP_LEVEL}/lib/
cp -r ${SRC_LIB_DIR}* ${BUILD_TARGET_DIR}/lib
