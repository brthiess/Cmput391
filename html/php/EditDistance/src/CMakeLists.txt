include_directories(${Algorithm_SOURCE_DIR}/include)

# Non-Intrinsic Sources.
file(GLOB SRC_FILES "*.cpp")
add_library(Algorithm ${SRC_FILES})
